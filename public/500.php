<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Helpers/Layout.php';

http_response_code(500);

layoutRenderHead([
    'title' => '500 - Gangguan Server | CukurGo',
    'description' => 'Sedang terjadi gangguan server. Silakan coba beberapa saat lagi.',
]);
?>
<body class="bg-light">
    <?php layoutRenderNavbar('public'); ?>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-lg border-0 text-center p-4 p-md-5">
                    <h1 class="display-5 text-gold fw-bold mb-3">500</h1>
                    <h2 class="h4 mb-3">Terjadi gangguan sistem</h2>
                    <p class="text-muted mb-4">Permintaan Anda belum bisa diproses saat ini. Mohon coba lagi beberapa saat lagi.</p>
                    <a href="Index.php" class="btn btn-dark">Coba lagi dari halaman booking</a>
                </div>
            </div>
        </div>
    </main>
    <?php layoutRenderFooter(); ?>
</body>
</html>
