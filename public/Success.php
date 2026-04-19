<?php

require_once __DIR__ . '/../vendor/autoload.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil - CukurGo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .success-icon {
            font-size: 80px;
            color: var(--primary-accent);
            margin-bottom: 20px;
        }
        .container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="container text-center">
        <div class="col-md-6">
            <div class="card shadow-lg p-5">
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                        <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                        <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
                    </svg>
                </div>
                <h2 class="mb-3 fw-bold text-gold">Booking Berhasil!</h2>
                <p class="text-gold mb-4">
                    Terima kasih telah memilih <strong>CukurGo</strong>.<br> 
                    Data reservasi Anda telah kami terima. Silakan datang tepat waktu sesuai jadwal yang Anda pilih.
                </p>
                <div class="d-grid gap-2">
                    <a href="index.php" class="btn btn-dark py-3">Buat Booking Baru</a>
                    <a href="https://wa.me/6283155811515" target="_blank" class="btn btn-outline-secondary py-2">
                        Hubungi Admin via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>