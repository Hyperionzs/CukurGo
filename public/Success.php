<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Helpers/Layout.php';

layoutRenderHead([
    'title' => 'Booking Berhasil - CukurGo',
    'description' => 'Reservasi Anda telah kami terima. Sampai jumpa di CukurGo.',
    'extra_head' => '<style>
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
    </style>',
]);
?>
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
                <div class="d-grid gap-2 mt-4">
                    <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=Jadwal+Potong+Rambut+di+CukurGo&details=Jangan+lupa+jadwal+cukur+Anda+di+CukurGo+Barbershop!&location=CukurGo+Barbershop" target="_blank" class="btn btn-outline-gold py-2 fw-bold d-flex align-items-center justify-content-center gap-2 border border-gold" style="color: var(--primary-accent);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-plus" viewBox="0 0 16 16"><path d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                        Tambahkan ke Kalender
                    </a>
                    <button class="btn btn-outline-info py-2 fw-bold d-flex align-items-center justify-content-center gap-2" onclick="alert('✅ Pengingat WhatsApp berhasil diaktifkan! Kami akan mengirimkan pesan 1 jam sebelum jadwal Anda.')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/></svg>
                        Aktifkan Pengingat
                    </button>
                    <a href="Index.php" class="btn btn-dark py-3 mt-3 fw-bold border border-secondary border-opacity-50">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
    <?php layoutRenderFooter('Terima kasih sudah booking di CukurGo.'); ?>
</body>
</html>