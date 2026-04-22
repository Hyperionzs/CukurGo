<?php

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Helpers/Csrf.php';

class BookingController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * @return array{ok: bool, message?: string}
     */
    public function createBooking(array $data): array
    {
        if (!Csrf::validate($data['csrf_token'] ?? null)) {
            return ['ok' => false, 'message' => 'Permintaan tidak valid. Muat ulang halaman dan coba lagi.'];
        }

        $name = isset($data['name']) ? trim((string) $data['name']) : '';
        $phoneRaw = isset($data['phone']) ? (string) $data['phone'] : '';
        $serviceId = isset($data['service_id']) ? $data['service_id'] : '';
        $date = isset($data['date']) ? trim((string) $data['date']) : '';
        $time = isset($data['time']) ? trim((string) $data['time']) : '';

        $name = strip_tags($name);
        if ($name === '' || mb_strlen($name) > 120) {
            return ['ok' => false, 'message' => 'Nama tidak valid.'];
        }

        $phone = $this->normalizeIndonesiaPhone($phoneRaw);
        if ($phone === null) {
            return ['ok' => false, 'message' => 'Format nomor WhatsApp tidak valid. Gunakan contoh: 081234567890.'];
        }

        $sidStr = (string) $serviceId;
        if ($sidStr === '' || !ctype_digit($sidStr)) {
            return ['ok' => false, 'message' => 'Layanan tidak valid.'];
        }
        $serviceIdInt = (int) $sidStr;
        if ($serviceIdInt < 1) {
            return ['ok' => false, 'message' => 'Layanan tidak valid.'];
        }
        if (!$this->serviceExists($serviceIdInt)) {
            return ['ok' => false, 'message' => 'Layanan yang dipilih tidak tersedia.'];
        }

        $dateErr = $this->validateBookingDate($date);
        if ($dateErr !== null) {
            return ['ok' => false, 'message' => $dateErr];
        }

        $timeErr = $this->validateBookingTime($time);
        if ($timeErr !== null) {
            return ['ok' => false, 'message' => $timeErr];
        }

        $checkQuery = 'SELECT id FROM reservations WHERE reservation_date = :date AND reservation_time = :time AND status != \'cancelled\' LIMIT 1';
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->execute([
            ':date' => $date,
            ':time' => $time,
        ]);
        if ($checkStmt->rowCount() > 0) {
            return ['ok' => false, 'message' => 'Maaf, slot waktu sudah dibooking orang lain. Silakan pilih jam atau tanggal lain.'];
        }

        $query = 'INSERT INTO reservations (customer_name, phone_number, service_id, reservation_date, reservation_time) 
                  VALUES (:name, :phone, :service, :date, :time)';

        $stmt = $this->db->prepare($query);
        $nameSafe = htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8');

        try {
            $stmt->execute([
                ':name' => $nameSafe,
                ':phone' => $phone,
                ':service' => $serviceIdInt,
                ':date' => $date,
                ':time' => $time,
            ]);
            return ['ok' => true];
        } catch (PDOException $e) {
            return ['ok' => false, 'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'];
        }
    }

    private function serviceExists(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM services WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() !== false;
    }

    /**
     * Digits-only WA number, normalized to 62…
     */
    private function normalizeIndonesiaPhone(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits === null || $digits === '') {
            return null;
        }
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }
        if (!str_starts_with($digits, '62')) {
            return null;
        }
        $len = strlen($digits);
        if ($len < 11 || $len > 14) {
            return null;
        }
        return $digits;
    }

    private function validateBookingDate(string $date): ?string
    {
        $tz = new DateTimeZone('Asia/Jakarta');
        $d = DateTime::createFromFormat('Y-m-d', $date, $tz);
        if ($d === false || $d->format('Y-m-d') !== $date) {
            return 'Tanggal tidak valid.';
        }
        $today = (new DateTime('today', $tz))->format('Y-m-d');
        if ($date < $today) {
            return 'Tanggal tidak boleh di masa lalu.';
        }
        $maxDays = (int) ($_ENV['BOOKING_MAX_DAYS_AHEAD'] ?? getenv('BOOKING_MAX_DAYS_AHEAD') ?: 90);
        if ($maxDays < 1) {
            $maxDays = 90;
        }
        $max = (new DateTime('today', $tz))->modify('+' . $maxDays . ' days')->format('Y-m-d');
        if ($date > $max) {
            return 'Tanggal terlalu jauh ke depan. Pilih tanggal yang lebih dekat.';
        }
        return null;
    }

    private function validateBookingTime(string $time): ?string
    {
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $time)) {
            return 'Jam tidak valid.';
        }
        $open = $_ENV['BOOKING_OPEN_TIME'] ?? getenv('BOOKING_OPEN_TIME') ?: '08:00';
        $close = $_ENV['BOOKING_CLOSE_TIME'] ?? getenv('BOOKING_CLOSE_TIME') ?: '20:00';
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $open) || !preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $close)) {
            $open = '08:00';
            $close = '20:00';
        }
        if ($time < $open || $time > $close) {
            return 'Jam booking hanya antara ' . $open . ' dan ' . $close . ' WIB.';
        }
        return null;
    }
}
