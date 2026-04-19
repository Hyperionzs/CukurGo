<?php
// public/dashboard.php

// 1. Load Autoload & Dependencies
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Helpers/Utils.php';

// 2. Koneksi Database
$database = new Database();
$db = $database->getConnection();

// 3. Query Data Reservasi (Join dengan tabel services)
$query = "SELECT r.*, s.name as service_name, s.price 
          FROM reservations r 
          JOIN services s ON r.service_id = s.id 
          ORDER BY r.reservation_date ASC, r.reservation_time ASC";

$stmt = $db->prepare($query);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CukurGo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-dark bg-dark border-bottom border-secondary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-gold" href="#">CukurGo ADMIN</a>
            <span class="text-muted small">Panel Monitoring Antrean</span>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0 text-gold fw-bold">Daftar Reservasi Masuk</h5>
                        <button class="btn btn-sm btn-outline-warning" onclick="window.location.reload()">Refresh Data</button>
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
                                        <tr>
                                            <td class="ps-4 fw-bold"><?= Utils::sanitize($row['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td>
                                                <div class="fw-semibold"><?= Utils::formatDateIndo($row['reservation_date']) ?></div>
                                                <small class="text-muted"><?= substr($row['reservation_time'], 0, 5) ?> WIB</small>
                                            </td>
                                            <td class="text-gold"><?= Utils::formatRupiah($row['price']) ?></td>
                                            <td>
                                                <a href="https://wa.me/<?= $row['phone_number'] ?>" target="_blank" class="btn btn-sm btn-success">
                                                    WhatsApp
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary p-2">
                                                    <?= strtoupper($row['status']) ?>
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

</body>
</html>