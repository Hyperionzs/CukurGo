<?php

require_once __DIR__ . '/../app/Helpers/Layout.php';

layoutRenderHead([
    'title' => 'Kebijakan Privasi - CukurGo',
    'description' => 'Informasi singkat kebijakan privasi CukurGo terkait penggunaan data pelanggan.',
    'canonical_url' => '/Privacy.php',
]);
?>
<body class="bg-dark text-white">
    <?php layoutRenderNavbar('public'); ?>
    
    <!-- Header Section -->
    <header class="py-5 bg-black border-bottom border-dark">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-3"><span class="text-gold">Kebijakan</span> Privasi</h1>
            <p class="lead text-white mb-0" style="max-width: 600px; margin: 0 auto;">
                Komitmen kami dalam menjaga dan melindungi data pribadi Anda sebagai pelanggan setia CukurGo.
            </p>
        </div>
    </header>

    <div class="container py-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Content Card -->
                <div class="bg-card p-4 p-md-5 rounded-4 shadow-lg position-relative overflow-hidden" style="border: 1px solid rgba(255,255,255,0.05);">
                    <!-- Decorative Background Element -->
                    <div class="position-absolute top-0 end-0 p-4" style="opacity: 0.03; pointer-events: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" fill="currentColor" class="bi bi-shield-lock-fill" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5z"/>
                        </svg>
                    </div>

                    <p class="text-white text-center mb-5" style="font-size: 1.1rem; line-height: 1.8;">
                        CukurGo sangat menghargai privasi Anda. Kami menerapkan standar tinggi dalam pengelolaan informasi untuk memastikan data Anda aman. Kami hanya mengumpulkan data yang esensial untuk keperluan proses booking layanan barbershop kami.
                    </p>

                    <!-- Section 1 -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-gold-subtle text-gold p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-database" viewBox="0 0 16 16"><path d="M4.318 10.354l8.367-1.46c.55-.096.956-.554.956-1.111 0-.585-.436-1.077-1.01-1.127l-8.081-1.127A4.246 4.246 0 0 0 1.05 10.23l2.253 1.135a3.18 3.18 0 0 1 1.015-1.01zm-2.45 2.213L4.3 13.916a2.636 2.636 0 0 0 .524.167v1.417h6.634v-1.4c.162-.036.319-.086.467-.152l2.365-1.312A2.083 2.083 0 0 0 15.5 10.79V6.366c0-1.572-1.89-2.866-4.22-2.866S7.06 4.794 7.06 6.366v1.39l-4.103-.572a1.731 1.731 0 0 0-1.928 1.408l-1 5.378a1.35 1.35 0 0 0 1.84 1.6zM8.5 4.5c2.148 0 3.22 1.07 3.22 1.866 0 .796-1.072 1.866-3.22 1.866-2.148 0-3.22-1.07-3.22-1.866C5.28 5.57 6.352 4.5 8.5 4.5z"/></svg>
                            </div>
                            <h2 class="h5 fw-bold mb-0 text-gold">Data yang Kami Simpan</h2>
                        </div>
                        <div class="ps-5">
                            <ul class="text-white" style="line-height: 1.8;">
                                <li><strong>Nama Pelanggan:</strong> Untuk identifikasi antrean.</li>
                                <li><strong>Nomor WhatsApp Aktif:</strong> Untuk keperluan konfirmasi dan pengingat jadwal.</li>
                                <li><strong>Detail Booking:</strong> Pilihan layanan, tanggal, dan jam kedatangan Anda.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-gold-subtle text-gold p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16"><path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/><path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/></svg>
                            </div>
                            <h2 class="h5 fw-bold mb-0 text-gold">Tujuan Penggunaan Data</h2>
                        </div>
                        <div class="ps-5">
                            <ul class="text-white" style="line-height: 1.8;">
                                <li>Memproses dan menjadwalkan booking pelanggan.</li>
                                <li>Menghubungi pelanggan melalui WhatsApp terkait konfirmasi, pengingat, atau perubahan jadwal darurat.</li>
                                <li>Memastikan riwayat antrean dan pelayanan tercatat dengan rapi demi efisiensi operasional.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-gold-subtle text-gold p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-shield-check" viewBox="0 0 16 16"><path d="M5.338 1.59a59.768 59.768 0 0 0-2.836.856c-.99.333-1.6 1.12-1.6 2.062 0 1.84.02 4.14.1 6.131.08 2.054.49 4.135 1.76 5.568.64.71 1.48 1.16 2.5 1.34 1.15.2 2.37.2 3.65 0 1.02-.18 1.86-.63 2.5-1.34 1.27-1.433 1.68-3.514 1.76-5.568.08-1.99.1-4.291.1-6.131 0-.942-.61-1.73-1.6-2.062a59.768 59.768 0 0 0-2.836-.856C9.176 1.27 8.583 1 8 1s-1.176.27-2.662.59zM8 2.316a58.4 58.4 0 0 1 2.5.76C11.55 3.39 12 3.96 12 4.608c0 1.78-.016 3.98-.075 5.89-.06 1.9-.384 3.585-1.254 4.545-.5.54-1.09.84-1.802.99-1.04.22-2.13.22-3.17 0-.71-.15-1.3-.45-1.8-.99-.87-.96-1.19-2.64-1.25-4.54C2.016 8.58 2 6.38 2 4.608c0-.65.45-1.22 1.5-1.53a58.4 58.4 0 0 1 2.5-.76C6.88 2.115 7.42 2 8 2zm.854 4.854a.5.5 0 0 0-.708 0l-1.5 1.5a.5.5 0 0 0 .708.708l1.146-1.147 2.146 2.147a.5.5 0 0 0 .708-.708l-2.5-2.5z"/></svg>
                            </div>
                            <h2 class="h5 fw-bold mb-0 text-gold">Akses dan Keamanan Data</h2>
                        </div>
                        <div class="ps-5">
                            <p class="text-white" style="line-height: 1.8;">
                                Kami menjaga kerahasiaan Anda dengan ketat. Data hanya dapat diakses oleh admin atau petugas kasir yang berwenang untuk tujuan operasional. <strong class="text-white">CukurGo tidak pernah memperjualbelikan atau membagikan data Anda kepada pihak ketiga mana pun.</strong>
                            </p>
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-gold-subtle text-gold p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16"><path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-1.002.955l-.653-.758c.185-.145.364-.3.535-.46zM8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0-1A6 6 0 1 0 8 2a6 6 0 0 0 0 12zm2-6H8V3.5a.5.5 0 0 0-1 0V8a.5.5 0 0 0 .5.5h2.5a.5.5 0 0 0 0-1z"/></svg>
                            </div>
                            <h2 class="h5 fw-bold mb-0 text-gold">Masa Simpan Data</h2>
                        </div>
                        <div class="ps-5">
                            <p class="text-white" style="line-height: 1.8;">
                                Data Anda akan disimpan selama masih diperlukan untuk kebutuhan pelacakan riwayat booking Anda. Namun, data secara otomatis atau berkala dapat dihapus sesuai dengan kebijakan privasi dan keamanan internal kami demi menghindari penumpukan informasi.
                            </p>
                        </div>
                    </div>

                    <!-- Section 5 -->
                    <div class="mt-5 pt-4 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                        <h2 class="h6 fw-bold text-gold mb-2">Butuh Bantuan Lebih Lanjut?</h2>
                        <p class="text-white small mb-0">
                            Jika Anda memiliki pertanyaan lebih rinci mengenai kebijakan privasi kami, silakan hubungi tim dukungan pelanggan CukurGo di kontak resmi yang tersedia.
                        </p>
                    </div>

                </div>
                
                <!-- Back Button -->
                <div class="text-center mt-5">
                    <a href="Index.php" class="btn btn-outline-gold px-4 py-2 rounded-pill d-inline-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php layoutRenderFooter('CukurGo - Kami menjaga data pelanggan dengan standar keamanan terbaik.'); ?>
</body>
</html>
