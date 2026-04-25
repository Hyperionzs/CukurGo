<?php 

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Controller/BookingController.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Helpers/Csrf.php';
require_once __DIR__ . '/../app/Helpers/Layout.php';

$error_message = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking = new BookingController();
    $result = $booking->createBooking($_POST);
    if (($result['ok'] ?? false) === true) {
        header("Location: Success.php");
        exit();
    } else {
        $detailMessage = $result['message'] ?? 'Silakan coba lagi.';
        $error_message = 'Booking gagal: ' . $detailMessage;
    }
}

$db = (new Database())->getConnection();
$stmt = $db->query("SELECT * FROM services");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
layoutRenderHead([
    'title' => 'CukurGo - Booking Barbershop Online',
    'description' => 'Booking barbershop online dengan cepat, aman, dan tanpa antre panjang.',
]);
?>
<body class="bg-light">
    <?php layoutRenderNavbar('public'); ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if ($error_message !== null && $error_message !== ''): ?>
                    <div class="alert alert-danger border-0 shadow-sm mb-4" style="background-color: #ff4d4d; color: white;">
                        <?= htmlspecialchars((string) $error_message, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-dark text-white p-3">
                        <h4 class="mb-0 text-center">CukurGo Booking</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor WhatsApp</label>
                                <input type="text" name="phone" class="form-control" placeholder="Contoh: 0812345678" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilih Layanan</label>
                                <select name="service_id" class="form-select" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    <?php foreach($services as $s): ?>
                                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="date" class="form-control" required min="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Jam</label>
                                    <input type="time" name="time" class="form-control" required>
                                </div>
                            </div>
                            <p class="small text-gold mb-3">
                                Dengan booking, Anda menyetujui penggunaan data nama dan nomor WhatsApp untuk proses konfirmasi jadwal layanan.
                                Baca detailnya di
                                <a href="Privacy.php" target="_blank" rel="noopener noreferrer">Kebijakan Privasi</a>.
                            </p>
                            <button type="submit" class="btn btn-dark w-100 py-2">Booking Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php layoutRenderFooter(); ?>
</body>
</html>