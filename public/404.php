<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Helpers/Layout.php';

http_response_code(404);

layoutRenderHead([
    'title' => '404 - Halaman Tidak Ditemukan | CukurGo',
    'description' => 'Halaman yang Anda cari tidak ditemukan.',
]);
?>
<body class="bg-light">
    <?php layoutRenderNavbar('public'); ?>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-lg border-0 text-center p-4 p-md-5">
                    <h1 class="display-5 text-gold fw-bold mb-3">404</h1>
                    <h2 class="h4 mb-3">Halaman tidak ditemukan</h2>
                    <p class="text-muted mb-4">Link yang Anda buka tidak tersedia atau sudah dipindahkan.</p>
                    <a href="Index.php" class="btn btn-dark">Kembali ke halaman booking</a>
                </div>
            </div>
        </div>
    </main>
    <?php layoutRenderFooter(); ?>
</body>
</html>
