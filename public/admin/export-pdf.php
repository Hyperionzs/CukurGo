<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Config/Database.php';
require_once __DIR__ . '/../../app/Helpers/AdminAuth.php';
require_once __DIR__ . '/../../app/Helpers/Utils.php';

use Dompdf\Dompdf;
use Dompdf\Options;

new Database();
AdminAuth::requireAdmin();

$filter = $_GET['filter'] ?? 'all';

$database = new Database();
$db = $database->getConnection();

$whereClause = '';
if ($filter === 'today') {
    $todayStr = (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d');
    $whereClause = " WHERE r.reservation_date = '{$todayStr}'";
} elseif (in_array($filter, ['pending','confirmed','cancelled','completed'])) {
    $filterMap = ['pending'=>'Pending','confirmed'=>'Confirmed','cancelled'=>'Cancelled','completed'=>'Completed'];
    $dbStatus = $filterMap[$filter] ?? 'Pending';
    $whereClause = " WHERE r.status = '{$dbStatus}'";
}

$query = "SELECT r.*, s.name as service_name, s.price
          FROM reservations r
          JOIN services s ON r.service_id = s.id
          {$whereClause}
          ORDER BY r.reservation_date DESC, r.reservation_time ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));

// -- Build HTML for PDF --
$filterLabel = match($filter) {
    'today' => 'Hari Ini',
    'pending' => 'Pending',
    'confirmed' => 'Dikonfirmasi',
    'cancelled' => 'Dibatalkan',
    'completed' => 'Selesai',
    default => 'Semua Reservasi',
};

$statusBadge = fn($s) => match($s) {
    'Pending' => '<span style="background:#ffca2c22;color:#ffca2c;padding:3px 10px;border-radius:50px;font-size:11px;font-weight:600;">Pending</span>',
    'Confirmed' => '<span style="background:#75b79822;color:#75b798;padding:3px 10px;border-radius:50px;font-size:11px;font-weight:600;">Dikonfirmasi</span>',
    'Arrived' => '<span style="background:#17a2b822;color:#17a2b8;padding:3px 10px;border-radius:50px;font-size:11px;font-weight:600;">Arrived</span>',
    'In Progress' => '<span style="background:#d4af3722;color:#d4af37;padding:3px 10px;border-radius:50px;font-size:11px;font-weight:600;">In Progress</span>',
    'Completed' => '<span style="background:#6ea8fe22;color:#6ea8fe;padding:3px 10px;border-radius:50px;font-size:11px;font-weight:600;">Selesai</span>',
    'Cancelled' => '<span style="background:#ff6b6b22;color:#ff6b6b;padding:3px 10px;border-radius:50px;font-size:11px;font-weight:600;">Dibatalkan</span>',
    default => $s,
};

$rows = '';
foreach ($bookings as $b) {
    $wa = preg_replace('/\D+/', '', (string) $b['phone_number']);
    $rows .= '<tr>
        <td>' . htmlspecialchars($b['customer_name'], ENT_QUOTES, 'UTF-8') . '</td>
        <td>' . htmlspecialchars($b['service_name'], ENT_QUOTES, 'UTF-8') . '</td>
        <td>' . Utils::formatDateIndo($b['reservation_date']) . '</td>
        <td>' . substr($b['reservation_time'], 0, 5) . ' WIB</td>
        <td>Rp ' . number_format($b['price'] ?? 0, 0, ',', '.') . '</td>
        <td style="text-align:center;">' . $statusBadge($b['status']) . '</td>
    </tr>';
}

if ($rows === '') {
    $rows = '<tr><td colspan="6" style="text-align:center;padding:30px;color:#666;">Tidak ada data reservasi.</td></tr>';
}

$html = '
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Reservasi - CukurGo</title>
<style>
    @page { margin: 20mm 15mm; }
    body { font-family: "DejaVu Sans", sans-serif; color: #222; font-size: 11px; }
    .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #d4af37; }
    .header h1 { margin: 0; font-size: 22px; color: #d4af37; text-transform: uppercase; letter-spacing: 2px; }
    .header p { margin: 5px 0 0; color: #666; font-size: 11px; }
    .filter-info { margin-bottom: 15px; font-size: 12px; color: #555; }
    .filter-info strong { color: #d4af37; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background: #d4af37; color: #000; padding: 8px 6px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; }
    td { padding: 7px 6px; border-bottom: 1px solid #ddd; }
    tr:nth-child(even) { background: #f9f9f9; }
    .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
    .stats { margin-bottom: 15px; }
    .stats table { width: auto; margin: 0; }
    .stats td { border: none; padding: 2px 15px 2px 0; font-size: 11px; }
    .stats td:first-child { font-weight: bold; }
</style>
</head>
<body>
    <div class="header">
        <h1>CukurGo</h1>
        <p>Laporan ' . $filterLabel . ' — Diterbitkan ' . $now->format('d/m/Y H:i') . ' WIB</p>
    </div>

    <div class="stats">
        <table>
            <tr><td>Total Data</td><td>: ' . count($bookings) . ' reservasi</td></tr>
            <tr><td>Filter</td><td>: <strong>' . $filterLabel . '</strong></td></tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Harga</th>
                <th style="text-align:center;">Status</th>
            </tr>
        </thead>
        <tbody>
            ' . $rows . '
        </tbody>
    </table>

    <div class="footer">
        CukurGo — Barbershop Booking System. Dokumen ini digenerate otomatis pada ' . $now->format('d/m/Y H:i') . ' WIB.
    </div>
</body>
</html>';

// -- Render PDF --
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$filename = 'CukurGo-Laporan-' . $filterLabel . '-' . $now->format('Ymd-His') . '.pdf';

$dompdf->stream($filename, ['Attachment' => true]);
exit;