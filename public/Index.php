<?php 

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Controller/BookingController.php';
require_once __DIR__ . '/../app/Config/Database.php';
require_once __DIR__ . '/../app/Helpers/Csrf.php';
require_once __DIR__ . '/../app/Helpers/Layout.php';

$error_message = null;

// AJAX Handler untuk cek ketersediaan slot via JavaScript
if (isset($_GET['action']) && $_GET['action'] === 'get_availability' && isset($_GET['date'])) {
    header('Content-Type: application/json');
    $ctrl = new BookingController();
    echo json_encode($ctrl->getAvailability($_GET['date']));
    exit;
}

$bookingCtrl = new BookingController();
$initialDate = date('Y-m-d');
$availability = $bookingCtrl->getAvailability($initialDate);

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
                        <button type="button" class="btn btn-gold btn-lg text-dark fw-bold px-4 rounded-pill shadow" data-bs-toggle="modal" data-bs-target="#bookingModal">Booking Sekarang</button>
                        <a href="#features-section" class="btn btn-outline-light btn-lg fw-bold px-4 rounded-pill">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
                <div class="col-md-6 position-relative hero-image-wrapper">
                    <div class="hero-image-decor"></div>
                    <img src="assets/img/hero-barber.png" alt="Premium Barbershop" class="img-fluid hero-image rounded-4 shadow-lg animate-float" style="object-fit: cover; height: 500px; width: 100%;">
                </div>
            </div>
        </div>
        <div class="hero-overlay"></div>
    </header>

    <!-- Features Section -->
    <section id="features-section" class="py-5 bg-black position-relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at 10% 20%, rgba(212, 175, 55, 0.05) 0%, transparent 40%), radial-gradient(circle at 90% 80%, rgba(212, 175, 55, 0.05) 0%, transparent 40%); pointer-events: none;"></div>
        
        <div class="container py-5 position-relative z-1">
            <div class="text-center mb-5 section-title animate-slide-up">
                <h6 class="text-gold fw-bold text-uppercase tracking-wider" style="letter-spacing: 2px;">Keunggulan Kami</h6>
                <h2 class="display-5 fw-bold mb-3">Kenapa Memilih CukurGo?</h2>
                <div class="divider mx-auto mb-4"></div>
                <p class="lead text-light opacity-75 mx-auto mb-0" style="max-width: 650px; font-weight: 400; line-height: 1.7;">Kami menggabungkan seni cukur rambut klasik dengan kepraktisan teknologi modern untuk menciptakan pengalaman <span class="text-gold fw-semibold fst-italic">grooming</span> tak terlupakan untuk Anda.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4 animate-slide-up-delay">
                    <div class="feature-card p-5 rounded-4 shadow-lg h-100 text-center position-relative overflow-hidden" style="background: linear-gradient(145deg, #161616 0%, #1e1e1e 100%);">
                        <div class="feature-glow position-absolute top-0 start-50 translate-middle-x" style="width: 120px; height: 120px; background: rgba(212,175,55,0.15); filter: blur(40px);"></div>
                        <div class="icon-box mx-auto mb-4 bg-black rounded-circle d-flex align-items-center justify-content-center border border-gold shadow" style="width: 80px; height: 80px; transition: all 0.4s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="var(--primary-accent)" viewBox="0 0 16 16">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3 text-white" style="letter-spacing: 0.5px;">Tanpa Antre</h4>
                        <p class="text-light opacity-75 fw-light mb-0" style="font-size: 1.05rem; line-height: 1.7;">Pilih jam kedatangan Anda. Datang, duduk, dan langsung dilayani tanpa membuang waktu berharga di ruang tunggu.</p>
                    </div>
                </div>
                <div class="col-md-4 animate-slide-up-delay-2">
                    <div class="feature-card p-5 rounded-4 shadow-lg h-100 text-center position-relative overflow-hidden" style="background: linear-gradient(145deg, #161616 0%, #1e1e1e 100%);">
                        <div class="feature-glow position-absolute top-0 start-50 translate-middle-x" style="width: 120px; height: 120px; background: rgba(212,175,55,0.15); filter: blur(40px);"></div>
                        <div class="icon-box mx-auto mb-4 bg-black rounded-circle d-flex align-items-center justify-content-center border border-gold shadow" style="width: 80px; height: 80px; transition: all 0.4s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="var(--primary-accent)" viewBox="0 0 16 16">
                                <path d="M12.96 1.469c.154.31.154.689 0 1-.154.31-.448.514-.793.514-.344 0-.638-.203-.792-.514-.154-.31-.154-.689 0-1 .154-.31.448-.515.792-.515.345 0 .639.204.793.515zM4 11H2V4h2v7zm1-7v7h7V4H5zm4.5 4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3 text-white" style="letter-spacing: 0.5px;">Stylist Profesional</h4>
                        <p class="text-light opacity-75 fw-light mb-0" style="font-size: 1.05rem; line-height: 1.7;">Barber pilihan kami sangat berpengalaman dan siap memberikan gaya rambut berkelas sesuai bentuk wajah karakter Anda.</p>
                    </div>
                </div>
                <div class="col-md-4 animate-slide-up-delay">
                    <div class="feature-card p-5 rounded-4 shadow-lg h-100 text-center position-relative overflow-hidden" style="background: linear-gradient(145deg, #161616 0%, #1e1e1e 100%);">
                        <div class="feature-glow position-absolute top-0 start-50 translate-middle-x" style="width: 120px; height: 120px; background: rgba(212,175,55,0.15); filter: blur(40px);"></div>
                        <div class="icon-box mx-auto mb-4 bg-black rounded-circle d-flex align-items-center justify-content-center border border-gold shadow" style="width: 80px; height: 80px; transition: all 0.4s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="var(--primary-accent)" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                            </svg>
                        </div>
                        <h4 class="fw-bold mb-3 text-white" style="letter-spacing: 0.5px;">Privasi & Higienis</h4>
                        <p class="text-light opacity-75 fw-light mb-0" style="font-size: 1.05rem; line-height: 1.7;">Rasakan kenyamanan di ruangan yang bersih dan santai. Dedikasi kami untuk memberikan pengalaman relaksasi pria modern.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services-section" class="py-5 bg-dark">
        <div class="container py-5">
            <div class="text-center mb-5 section-title animate-slide-up">
                <h6 class="text-gold fw-bold text-uppercase tracking-wider" style="letter-spacing: 2px;">Layanan Kami</h6>
                <h2 class="display-5 fw-bold mb-3">Pilih Gaya Terbaikmu</h2>
                <div class="divider mx-auto mb-4"></div>
                <p class="lead text-light opacity-75 mx-auto mb-0" style="max-width: 600px;">Kombinasi teknik potong klasik dan modern untuk penampilan yang tak tertandingi.</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php foreach($services as $s): 
                    // Logika mapping gambar & badge sederhana
                    $imagePath = 'assets/img/service-haircut.png';
                    $isPopular = false;
                    $duration = '30 - 45 Menit';
                    
                    if (stripos($s['name'], 'Reguler') !== false) { $isPopular = true; }
                    if (stripos($s['name'], 'Cukur Jenggot') !== false) { $imagePath = 'assets/img/service-shaving.png'; $duration = '20 Menit'; }
                    elseif (stripos($s['name'], 'Spa') !== false || stripos($s['name'], 'Creambath') !== false) { $imagePath = 'assets/img/service-treatment.png'; $duration = '60 Menit'; }
                    elseif (stripos($s['name'], 'Pewarnaan') !== false || stripos($s['name'], 'Smoothing') !== false) { $imagePath = 'assets/img/service-treatment.png'; $duration = '90 - 120 Menit'; }
                ?>
                <div class="col-md-6 col-lg-4 animate-slide-up-delay">
                    <div class="service-card h-100 d-flex flex-column">
                        <div class="service-img-container">
                            <?php if($isPopular): ?>
                                <span class="service-badge-popular">Terpopuler</span>
                            <?php endif; ?>
                            <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($s['name']) ?>" class="service-card-img">
                        </div>
                        <div class="p-4 flex-grow-1 d-flex flex-column">
                            <div class="service-icon-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="var(--primary-accent)" viewBox="0 0 16 16">
                                    <path d="M4 .5a.5.5 0 0 0-1 0V2H2a2 2 0 0 0-2 2v1h1V4a1 1 0 0 1 1-1h1V.5zM1 6H0v1h1V6zm0 2H0v1h1V8zm0 2H0v1h1v-1zm12-9.5V2h1a1 1 0 0 1 1 1h1a2 2 0 0 0-2-2h-1V.5zM15 4h1v1h-1V4zm0 2h1v1h-1V6zm0 2h1v1h-1V8zm0 2h1v1h-1v-1zM1.5 15h1a1 1 0 0 1-1-1v-1H0a2 2 0 0 0 2 2h1v-1H1.5zM3 15h1v1H3v-1zm2 0h1v1H5v-1zm2 0h1v1H7v-1zm2 0h1v1H9v-1zm2 0h1v1h-1v-1zm2 0h1a2 2 0 0 0 2-2h-1a1 1 0 0 1-1 1v1zM4 4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V4z"/>
                                </svg>
                            </div>
                            <h4 class="fw-bold mb-2"><?= htmlspecialchars($s['name']) ?></h4>
                            <div class="service-info-footer">
                                <span class="d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                    </svg>
                                    <?= $duration ?>
                                </span>
                            </div>
                            <p class="text-light opacity-75 small mb-4 flex-grow-1">
                                <?= htmlspecialchars($s['description'] ?? 'Layanan perawatan premium untuk menunjang penampilan maksimal Anda.') ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <div class="service-price">Rp <?= number_format($s['price'] ?? 0, 0, ',', '.') ?></div>
                                <button type="button" class="btn btn-sm btn-gold px-3 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#bookingModal" onclick="selectService('<?= $s['id'] ?>', '<?= htmlspecialchars(addslashes($s['name']), ENT_QUOTES, 'UTF-8') ?>', 'Rp <?= number_format($s['price'] ?? 0, 0, ',', '.') ?>')">Booking</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="booking-wrapper rounded-5 shadow-lg overflow-hidden d-flex flex-column flex-md-row position-relative">
                    
                    <!-- Close button -->
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4 z-3" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="booking-info p-5 text-white d-none d-md-flex flex-column justify-content-center" style="background: linear-gradient(135deg, #1a1a1a 0%, #0d0d0d 100%); flex: 1;">
                        <h3 class="fw-bold mb-4">Siap Cukur <span class="text-gold">Maksimal?</span></h3>
                        <p class="mb-5 opacity-75" style="font-size: 1rem;">Amankan kursi Anda sekarang dan nikmati pengalaman grooming premium.</p>
                        
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-gold-subtle p-3 rounded-circle me-3 d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--primary-accent)" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Lokasi Kami</h6>
                                <p class="mb-0 text-gold small">Jl. Gaya Pria No. 1, Jakarta Selatan</p>
                            </div>
                        </div>
                    </div>
                    <div class="booking-form-container p-5 bg-card position-relative" style="flex: 1.5;">
                        <?php if ($error_message !== null && $error_message !== ''): ?>
                            <div class="alert alert-danger border-0 shadow-sm mb-4 rounded-3 d-flex align-items-center" style="background-color: rgba(255, 77, 77, 0.1); color: #ff4d4d;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                </svg>
                                <?= htmlspecialchars((string) $error_message, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>
                        
                        <h4 class="fw-bold mb-4 text-white" id="bookingModalLabel">Form Booking</h4>
                        <form action="" method="POST" class="booking-form">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                            
                            <div class="mb-4">
                                <label for="nameInput" class="form-label">Nama Lengkap</label>
                                <div class="input-icon-wrapper">
                                    <div class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/></svg>
                                    </div>
                                    <input type="text" name="name" class="form-control" id="nameInput" placeholder="Masukkan nama Anda" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="phoneInput" class="form-label">Nomor WhatsApp</label>
                                <div class="input-icon-wrapper">
                                    <div class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c-.003 1.396.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                                    </div>
                                    <input type="text" name="phone" class="form-control" id="phoneInput" placeholder="Contoh: 0812345678" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Layanan</label>
                                <div class="input-icon-wrapper">
                                    <div class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 3.5c-.614-.884-.074-1.962.858-2.5L8 7.226 11.642 1c.932.538 1.472 1.616.858 2.5L8.81 8.61l1.556 2.661a2.5 2.5 0 1 1-.798.635L8 9.36l-1.568 2.546a2.5 2.5 0 1 1-.798-.635L7.19 8.61 3.5 3.5zm2.5 4.082l.853-1.416L5.688 4.29 4.39 6.236 6 7.582zm4 0l1.61-1.346-1.298-1.945-.853 1.416L10 7.582zM4.5 14a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm7 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg>
                                    </div>
                                    <input type="hidden" name="service_id" id="serviceInput" required>
                                    <button class="form-control text-start dropdown-toggle shadow-none w-100 d-flex justify-content-between align-items-center text-white" type="button" id="serviceDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" style="padding-right: 1rem; cursor: pointer;">
                                        <span id="serviceDropdownText" class="text-muted">-- Pilih Layanan --</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark w-100 shadow-lg border-0 rounded-4 mt-2" aria-labelledby="serviceDropdownBtn" style="background: linear-gradient(145deg, #1e1e1e 0%, #161616 100%); border: 1px solid rgba(212, 175, 55, 0.2) !important;">
                                        <?php foreach($services as $s): ?>
                                            <li>
                                                <a class="dropdown-item py-2 px-3 service-select-item" href="#" data-value="<?= $s['id'] ?>" data-name="<?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8') ?>" data-price="Rp <?= number_format($s['price'] ?? 0, 0, ',', '.') ?>">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-semibold text-white"><?= htmlspecialchars($s['name']) ?></span>
                                                        <span class="badge bg-gold-subtle text-gold rounded-pill px-2 py-1">Rp <?= number_format($s['price'] ?? 0, 0, ',', '.') ?></span>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="dateInput" class="form-label">Tanggal Booking</label>
                                <div class="input-icon-wrapper">
                                    <div class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                                    </div>
                                    <input type="date" name="date" class="form-control" id="dateInput" required min="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-end mb-3">
                                    <label class="form-label mb-0">Pilih Waktu</label>
                                    <span class="badge bg-gold-subtle rounded-pill text-gold px-2 py-1" style="font-size: 0.7rem;">Live Availability</span>
                                </div>
                                
                                <ul class="nav nav-pills mb-3 justify-content-between slot-pills" id="pills-tab" role="tablist">
                                    <li class="nav-item flex-fill text-center mx-1" role="presentation">
                                        <button class="nav-link w-100 active rounded-pill py-2" id="pills-pagi-tab" data-bs-toggle="pill" data-bs-target="#pills-pagi" type="button" role="tab">Pagi</button>
                                    </li>
                                    <li class="nav-item flex-fill text-center mx-1" role="presentation">
                                        <button class="nav-link w-100 rounded-pill py-2" id="pills-siang-tab" data-bs-toggle="pill" data-bs-target="#pills-siang" type="button" role="tab">Siang</button>
                                    </li>
                                    <li class="nav-item flex-fill text-center mx-1" role="presentation">
                                        <button class="nav-link w-100 rounded-pill py-2" id="pills-malam-tab" data-bs-toggle="pill" data-bs-target="#pills-malam" type="button" role="tab">Malam</button>
                                    </li>
                                </ul>
                                
                                <div class="tab-content" id="pills-tabContent">
                                    <?php foreach ($availability as $sessionName => $slots): ?>
                                    <div class="tab-pane fade <?= $sessionName === 'pagi' ? 'show active' : '' ?>" id="pills-<?= $sessionName ?>" role="tabpanel">
                                        <div class="row g-2">
                                            <?php foreach ($slots as $slot): ?>
                                            <div class="col-4">
                                                <input type="radio" name="time" value="<?= $slot['time'] ?>" 
                                                       class="btn-check time-slot-input" 
                                                       id="time<?= str_replace(':', '', $slot['time']) ?>" 
                                                       <?= $slot['status'] === 'full' ? 'disabled' : '' ?> required>
                                                <label class="btn btn-outline-gold w-100 time-slot-chip <?= $slot['status'] === 'full' ? 'full' : '' ?>" 
                                                       for="time<?= str_replace(':', '', $slot['time']) ?>">
                                                    <span class="d-block fw-bold <?= $slot['status'] === 'full' ? 'text-muted' : '' ?>"><?= $slot['time'] ?></span>
                                                    <span class="d-block small text-<?= $slot['status'] === 'full' ? 'danger' : ($slot['status'] === 'warning' ? 'warning' : 'success') ?> slot-text">
                                                        <?= $slot['status'] === 'full' ? ($slot['is_past'] ? 'Selesai' : 'Penuh') : ($slot['status'] === 'warning' ? 'Sisa 1' : 'Tersedia') ?>
                                                    </span>
                                                </label>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <script>
                            document.getElementById('dateInput').addEventListener('change', function() {
                                const date = this.value;
                                const tabContent = document.getElementById('pills-tabContent');
                                tabContent.style.opacity = '0.5';
                                
                                fetch(`?action=get_availability&date=${date}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        for (const session in data) {
                                            const container = document.querySelector(`#pills-${session} .row`);
                                            if (!container) continue;
                                            container.innerHTML = '';
                                            data[session].forEach(slot => {
                                                const id = `time${slot.time.replace(':', '')}`;
                                                const isDisabled = slot.status === 'full' ? 'disabled' : '';
                                                const isFullClass = slot.status === 'full' ? 'full' : '';
                                                const textClass = slot.status === 'full' ? 'text-muted' : '';
                                                const statusClass = slot.status === 'full' ? 'danger' : (slot.status === 'warning' ? 'warning' : 'success');
                                                const statusText = slot.status === 'full' ? (slot.is_past ? 'Selesai' : 'Penuh') : (slot.status === 'warning' ? 'Sisa 1' : 'Tersedia');
                                                
                                                container.innerHTML += `
                                                    <div class="col-4">
                                                        <input type="radio" name="time" value="${slot.time}" class="btn-check time-slot-input" id="${id}" ${isDisabled} required>
                                                        <label class="btn btn-outline-gold w-100 time-slot-chip ${isFullClass}" for="${id}">
                                                            <span class="d-block fw-bold ${textClass}">${slot.time}</span>
                                                            <span class="d-block small text-${statusClass} slot-text">${statusText}</span>
                                                        </label>
                                                    </div>
                                                `;
                                            });
                                        }
                                        tabContent.style.opacity = '1';
                                    });
                            });
                            </script>
                            <p class="small text-gold mb-4 d-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-check me-2 mt-1 flex-shrink-0" viewBox="0 0 16 16">
                                    <path d="M5.338 1.59a59.768 59.768 0 0 0-2.836.856c-.99.333-1.6 1.12-1.6 2.062 0 1.84.02 4.14.1 6.131.08 2.054.49 4.135 1.76 5.568.64.71 1.48 1.16 2.5 1.34 1.15.2 2.37.2 3.65 0 1.02-.18 1.86-.63 2.5-1.34 1.27-1.433 1.68-3.514 1.76-5.568.08-1.99.1-4.291.1-6.131 0-.942-.61-1.73-1.6-2.062a59.768 59.768 0 0 0-2.836-.856C9.176 1.27 8.583 1 8 1s-1.176.27-2.662.59zM8 2.316a58.4 58.4 0 0 1 2.5.76C11.55 3.39 12 3.96 12 4.608c0 1.78-.016 3.98-.075 5.89-.06 1.9-.384 3.585-1.254 4.545-.5.54-1.09.84-1.802.99-1.04.22-2.13.22-3.17 0-.71-.15-1.3-.45-1.8-.99-.87-.96-1.19-2.64-1.25-4.54C2.016 8.58 2 6.38 2 4.608c0-.65.45-1.22 1.5-1.53a58.4 58.4 0 0 1 2.5-.76C6.88 2.115 7.42 2 8 2zm.854 4.854a.5.5 0 0 0-.708 0l-1.5 1.5a.5.5 0 0 0 .708.708l1.146-1.147 2.146 2.147a.5.5 0 0 0 .708-.708l-2.5-2.5z"/>
                                </svg>
                                <span>Dengan booking, Anda menyetujui <a href="Privacy.php" class="text-blue text-decoration-none border-bottom border-gold">Kebijakan Privasi</a>.</span>
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

    <?php layoutRenderFooter(); ?>
    
    <?php if ($error_message !== null && $error_message !== ''): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('bookingModal'), {
                keyboard: false
            });
            myModal.show();
        });
    </script>
    <?php endif; ?>

    <script>
        document.querySelectorAll('.service-select-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const val = this.getAttribute('data-value');
                const name = this.getAttribute('data-name');
                const price = this.getAttribute('data-price');
                
                document.getElementById('serviceInput').value = val;
                
                const btnText = document.getElementById('serviceDropdownText');
                btnText.innerHTML = `<span class="text-white fw-semibold">${name}</span> <span class="text-gold fw-bold ms-2">${price}</span>`;
                btnText.classList.remove('text-muted');
            });
        });

        function selectService(id, name, price) {
            document.getElementById('serviceInput').value = id;
            const btnText = document.getElementById('serviceDropdownText');
            btnText.innerHTML = `<span class="text-white fw-semibold">${name}</span> <span class="text-gold fw-bold ms-2">${price}</span>`;
            btnText.classList.remove('text-muted');
        }
    </script>
</body>
</html>