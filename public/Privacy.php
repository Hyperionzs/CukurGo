<?php

require_once __DIR__ . '/../app/Helpers/Layout.php';

layoutRenderHead([
    'title' => 'Kebijakan Privasi - CukurGo',
    'description' => 'Informasi singkat kebijakan privasi CukurGo terkait penggunaan data pelanggan.',
    'canonical_url' => '/Privacy.php',
]);
?>
<body class="bg-light">
    <?php layoutRenderNavbar('public'); ?>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h1 class="h4 mb-0">Kebijakan Privasi Singkat</h1>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            CukurGo menghargai privasi pelanggan. Kami hanya mengumpulkan data yang diperlukan untuk proses booking layanan barbershop.
                        </p>

                        <h2 class="h6">Data yang kami simpan</h2>
                        <ul>
                            <li>Nama pelanggan.</li>
                            <li>Nomor WhatsApp aktif.</li>
                            <li>Detail booking: layanan, tanggal, dan jam kedatangan.</li>
                        </ul>

                        <h2 class="h6">Tujuan penggunaan data</h2>
                        <ul>
                            <li>Memproses dan menjadwalkan booking pelanggan.</li>
                            <li>Menghubungi pelanggan melalui WhatsApp terkait konfirmasi atau perubahan jadwal.</li>
                            <li>Memastikan riwayat antrean dan pelayanan tercatat dengan rapi.</li>
                        </ul>

                        <h2 class="h6">Akses dan keamanan data</h2>
                        <p>
                            Data hanya diakses oleh admin/petugas yang berwenang untuk operasional layanan CukurGo dan tidak diperjualbelikan kepada pihak lain.
                        </p>

                        <h2 class="h6">Masa simpan data</h2>
                        <p>
                            Data disimpan selama masih diperlukan untuk kebutuhan operasional dan layanan pelanggan, lalu dapat dihapus secara berkala sesuai kebijakan internal.
                        </p>

                        <h2 class="h6">Kontak</h2>
                        <p class="mb-0">
                            Untuk pertanyaan terkait privasi data, silakan hubungi admin CukurGo melalui kontak resmi yang tersedia.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php layoutRenderFooter('CukurGo - Kami menjaga data pelanggan untuk kebutuhan layanan.'); ?>
</body>
</html>
