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
<body class="bg-dark text-white">
    <?php layoutRenderNavbar('public'); ?>
    
    <!-- Hero Section -->
    <header class="hero-section d-flex align-items-center position-relative overflow-hidden">
        <div class="container text-center text-md-start position-relative z-2">
            <div class="row align-items-center">
                <div class="col-md-6 mb-5 mb-md-0 hero-content">
                    <span class="badge bg-gold text-dark mb-3 px-3 py-2 fw-bold rounded-pill shadow-sm animate-fade-in">+10,000 Pria Tampan Percaya Kami</span>
                    <h1 class="display-3 fw-bold mb-3 animate-slide-up" style="line-height: 1.2;">Gaya Rambut Sempurna, <br><span class="text-gold">Tanpa Antre Lama.</span></h1>
                    <p class="lead text-light opacity-75 mb-4 animate-slide-up-delay">Rasakan pengalaman cukur premium dengan stylist profesional kami. Booking jadwal Anda sekarang dan nikmati layanan kelas satu.</p>
                    <div class="d-flex gap-3 justify-content-center justify-content-md-start animate-slide-up-delay-2">
                        <a href="#booking-section" class="btn btn-gold btn-lg fw-bold px-4 rounded-pill shadow">Booking Sekarang</a>
                        <a href="#features-section" class="btn btn-outline-light btn-lg fw-bold px-4 rounded-pill">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
                <div class="col-md-6 position-relative hero-image-wrapper">
                    <div class="hero-image-decor"></div>
                    <img src="https://images.unsplash.com/photo-1585747860715-2ba37e788b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Premium Barbershop" class="img-fluid hero-image rounded-4 shadow-lg animate-float" style="object-fit: cover; height: 500px; width: 100%;">
                </div>
            </div>
        </div>
        <div class="hero-overlay"></div>
    </header>

    <!-- Features Section -->
    <section id="features-section" class="py-5 bg-black position-relative">
        <div class="container py-5">
            <div class="text-center mb-5 section-title">
                <h6 class="text-gold fw-bold text-uppercase tracking-wider" style="letter-spacing: 2px;">Keunggulan Kami</h6>
                <h2 class="display-5 fw-bold mb-3">Kenapa Memilih CukurGo?</h2>
                <div class="divider mx-auto mb-4"></div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card p-4 rounded-4 shadow h-100 text-center">
                        <div class="icon-box mx-auto mb-4 bg-gold-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="var(--primary-accent)" viewBox="0 0 16 16">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3">Tanpa Antre</h4>
                        <p class="text-muted">Pilih jam kedatangan. Datang, duduk, dan langsung dilayani tanpa perlu menunggu di ruang tunggu.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card p-4 rounded-4 shadow h-100 text-center">
                        <div class="icon-box mx-auto mb-4 bg-gold-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="var(--primary-accent)" viewBox="0 0 16 16">
                                <path d="M12.96 1.469c.154.31.154.689 0 1-.154.31-.448.514-.793.514-.344 0-.638-.203-.792-.514-.154-.31-.154-.689 0-1 .154-.31.448-.515.792-.515.345 0 .639.204.793.515zM4 11H2V4h2v7zm1-7v7h7V4H5zm4.5 4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3">Stylist Profesional</h4>
                        <p class="text-muted">Barber berpengalaman yang siap memberikan potongan rambut terbaik sesuai dengan bentuk dan karakter wajah Anda.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card p-4 rounded-4 shadow h-100 text-center">
                        <div class="icon-box mx-auto mb-4 bg-gold-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="var(--primary-accent)" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3">Privasi & Nyaman</h4>
                        <p class="text-muted">Nikmati suasana barbershop yang privat, bersih, dan nyaman. Pengalaman grooming sejati untuk pria modern.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Section -->
    <section id="booking-section" class="py-5 position-relative">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="booking-wrapper rounded-5 shadow-lg overflow-hidden d-flex flex-column flex-md-row">
                        <div class="booking-info p-5 text-white d-flex flex-column justify-content-center" style="background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%); flex: 1;">
                            <h3 class="fw-bold mb-4">Siap Cukur <span class="text-gold">Maksimal?</span></h3>
                            <p class="mb-5 opacity-75 lead" style="font-size: 1.1rem;">Hanya butuh waktu kurang dari 2 menit untuk mengamankan kursi Anda. Kami siap menyambut Anda.</p>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-gold-subtle p-3 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--primary-accent)" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">Lokasi Kami</h5>
                                    <p class="mb-0 text-muted">Jl. Gaya Pria No. 1, Jakarta Selatan</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <div class="bg-gold-subtle p-3 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--primary-accent)" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold">Jam Buka</h5>
                                    <p class="mb-0 text-muted">Setiap Hari: 09:00 - 22:00 WIB</p>
                                </div>
                            </div>
                        </div>
                        <div class="booking-form-container p-5 bg-card" style="flex: 1.5;">
                            <?php if ($error_message !== null && $error_message !== ''): ?>
                                <div class="alert alert-danger border-0 shadow-sm mb-4 rounded-3 d-flex align-items-center" style="background-color: rgba(255, 77, 77, 0.1); color: #ff4d4d;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </svg>
                                    <?= htmlspecialchars((string) $error_message, ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            <?php endif; ?>
                            
                            <h4 class="fw-bold mb-4 text-white">Form Booking</h4>
                            <form action="" method="POST" class="booking-form">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                                
                                <div class="form-floating mb-3">
                                    <input type="text" name="name" class="form-control" id="nameInput" placeholder="Masukkan nama Anda" required>
                                    <label for="nameInput">Nama Lengkap</label>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <input type="text" name="phone" class="form-control" id="phoneInput" placeholder="Contoh: 0812345678" required>
                                    <label for="phoneInput">Nomor WhatsApp</label>
                                </div>
                                
                                <div class="form-floating mb-3">
                                    <select name="service_id" class="form-select" id="serviceInput" required>
                                        <option value="" disabled selected>-- Pilih Layanan --</option>
                                        <?php foreach($services as $s): ?>
                                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> - Rp <?= number_format($s['price'] ?? 0, 0, ',', '.') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="serviceInput">Layanan</label>
                                </div>
                                
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="date" name="date" class="form-control" id="dateInput" required min="<?= date('Y-m-d') ?>">
                                            <label for="dateInput">Tanggal</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="time" name="time" class="form-control" id="timeInput" required>
                                            <label for="timeInput">Waktu</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <p class="small text-muted mb-4 d-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-check me-2 mt-1 flex-shrink-0" viewBox="0 0 16 16">
                                        <path d="M5.338 1.59a59.768 59.768 0 0 0-2.836.856c-.99.333-1.6 1.12-1.6 2.062 0 1.84.02 4.14.1 6.131.08 2.054.49 4.135 1.76 5.568.64.71 1.48 1.16 2.5 1.34 1.15.2 2.37.2 3.65 0 1.02-.18 1.86-.63 2.5-1.34 1.27-1.433 1.68-3.514 1.76-5.568.08-1.99.1-4.291.1-6.131 0-.942-.61-1.73-1.6-2.062a59.768 59.768 0 0 0-2.836-.856C9.176 1.27 8.583 1 8 1s-1.176.27-2.662.59zM8 2.316a58.4 58.4 0 0 1 2.5.76C11.55 3.39 12 3.96 12 4.608c0 1.78-.016 3.98-.075 5.89-.06 1.9-.384 3.585-1.254 4.545-.5.54-1.09.84-1.802.99-1.04.22-2.13.22-3.17 0-.71-.15-1.3-.45-1.8-.99-.87-.96-1.19-2.64-1.25-4.54C2.016 8.58 2 6.38 2 4.608c0-.65.45-1.22 1.5-1.53a58.4 58.4 0 0 1 2.5-.76C6.88 2.115 7.42 2 8 2zm.854 4.854a.5.5 0 0 0-.708 0l-1.5 1.5a.5.5 0 0 0 .708.708l1.146-1.147 2.146 2.147a.5.5 0 0 0 .708-.708l-2.5-2.5z"/>
                                    </svg>
                                    <span>Data Anda aman. Dengan booking, Anda menyetujui <a href="Privacy.php" class="text-gold text-decoration-none border-bottom border-gold">Kebijakan Privasi</a> kami.</span>
                                </p>
                                
                                <button type="submit" class="btn btn-gold w-100 py-3 fw-bold rounded-3 shadow d-flex justify-content-center align-items-center gap-2">
                                    <span>Konfirmasi Booking</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php layoutRenderFooter(); ?>
</body>
</html>