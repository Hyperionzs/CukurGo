<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Config/Database.php';
require_once __DIR__ . '/../../app/Helpers/Utils.php';
require_once __DIR__ . '/../../app/Helpers/AdminAuth.php';
require_once __DIR__ . '/../../app/Helpers/Layout.php';

new Database();

AdminAuth::requireAdmin();

$database = new Database();
$db = $database->getConnection();

$query = 'SELECT r.*, s.name as service_name, s.price 
          FROM reservations r 
          JOIN services s ON r.service_id = s.id 
          ORDER BY r.reservation_date ASC, r.reservation_time ASC';

$stmt = $db->prepare($query);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

layoutRenderHead([
    'title' => 'Admin Dashboard - CukurGo',
    'description' => 'Pantau daftar reservasi pelanggan CukurGo secara real-time.',
    'asset_prefix' => '../',
]);
?>
<body>
    <?php layoutRenderNavbar('admin'); ?>

    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0 text-gold fw-bold">Daftar Reservasi Masuk</h5>
                        <button class="btn btn-sm btn-outline-warning" type="button" onclick="window.location.reload()">Refresh Data</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Nama Pelanggan</th>
                                        <th>Layanan</th>
                                        <th>Jadwal</th>
                                        <th>Total Bayar</th>
                                        <th>Kontak</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($bookings) > 0): ?>
                                        <?php foreach ($bookings as $row): ?>
                                            <?php $waDigits = preg_replace('/\D+/', '', (string) $row['phone_number']); ?>
                                        <tr>
                                            <td class="ps-4 fw-bold"><?= Utils::sanitize($row['customer_name']) ?></td>
                                            <td><?= htmlspecialchars((string) $row['service_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td>
                                                <div class="fw-semibold"><?= Utils::formatDateIndo($row['reservation_date']) ?></div>
                                                <small class="text-muted"><?= htmlspecialchars(substr((string) $row['reservation_time'], 0, 5), ENT_QUOTES, 'UTF-8') ?> WIB</small>
                                            </td>
                                            <td class="text-gold"><?= Utils::formatRupiah($row['price']) ?></td>
                                            <td>
                                                <a href="https://wa.me/<?= htmlspecialchars($waDigits, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-success">
                                                    WhatsApp
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary p-2">
                                                    <?= htmlspecialchars(strtoupper((string) $row['status']), ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data reservasi hari ini.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php layoutRenderFooter('CukurGo Admin Dashboard.'); ?>
</body>
</html>
