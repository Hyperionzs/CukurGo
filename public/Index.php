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

    <!-- Testimonial Section -->
    <section id="testimonial-section" class="py-5 bg-black position-relative overflow-hidden">
        <div class="container py-5 position-relative z-1">
            <div class="text-center mb-5 section-title animate-slide-up">
                <h6 class="text-gold fw-bold text-uppercase tracking-wider" style="letter-spacing: 2px;">Testimonial</h6>
                <h2 class="display-5 fw-bold mb-3">Apa Kata Mereka?</h2>
                <div class="divider mx-auto mb-4"></div>
                <p class="lead text-light opacity-75 mx-auto mb-0" style="max-width: 600px;">Pengalaman pelanggan kami setelah mencoba layanan premium CukurGo.</p>
            </div>

            <div class="row justify-content-center animate-slide-up-delay">
                <div class="col-md-10 col-lg-8">
                    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators mb-0" style="bottom: -50px;">
                            <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner pb-5">
                            <div class="carousel-item active">
                                <div class="testimonial-card text-center p-4 p-md-5 rounded-4" style="background: linear-gradient(145deg, #1e1e1e 0%, #161616 100%); border: 1px solid rgba(212, 175, 55, 0.1);">
                                    <div class="rating-stars mb-4 text-gold fs-4">
                                        <i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i>
                                    </div>
                                    <h4 class="mb-3 text-white fst-italic">"Pelayanan luar biasa!"</h4>
                                    <p class="lead text-light opacity-75 mb-4 px-md-4">"Saya tidak perlu antre panjang lagi. Sistem bookingnya sangat mudah dan barbernya profesional. Hasil cukur memuaskan."</p>
                                    <div class="customer-info d-flex align-items-center justify-content-center">
                                        <div class="customer-avatar bg-gold text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">A</div>
                                        <div class="text-start">
                                            <h6 class="mb-0 fw-bold text-white">Andi Saputra</h6>
                                            <span class="small text-gold">Pelanggan Setia</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="testimonial-card text-center p-4 p-md-5 rounded-4" style="background: linear-gradient(145deg, #1e1e1e 0%, #161616 100%); border: 1px solid rgba(212, 175, 55, 0.1);">
                                    <div class="rating-stars mb-4 text-gold fs-4">
                                        <i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i>
                                    </div>
                                    <h4 class="mb-3 text-white fst-italic">"Tempat paling nyaman"</h4>
                                    <p class="lead text-light opacity-75 mb-4 px-md-4">"Interior barbershop sangat mewah dan bersih. CukurGo benar-benar memberikan standar baru untuk potong rambut pria di kota ini."</p>
                                    <div class="customer-info d-flex align-items-center justify-content-center">
                                        <div class="customer-avatar bg-gold text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">B</div>
                                        <div class="text-start">
                                            <h6 class="mb-0 fw-bold text-white">Budi Gunawan</h6>
                                            <span class="small text-gold">Pengguna Baru</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="testimonial-card text-center p-4 p-md-5 rounded-4" style="background: linear-gradient(145deg, #1e1e1e 0%, #161616 100%); border: 1px solid rgba(212, 175, 55, 0.1);">
                                    <div class="rating-stars mb-4 text-gold fs-4">
                                        <i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-fill mx-1"></i><i class="bi bi-star-half mx-1"></i>
                                    </div>
                                    <h4 class="mb-3 text-white fst-italic">"Sangat direkomendasikan!"</h4>
                                    <p class="lead text-light opacity-75 mb-4 px-md-4">"Layanan hot towel shave-nya juara. Barber paham dengan gaya yang saya inginkan tanpa perlu banyak menjelaskan."</p>
                                    <div class="customer-info d-flex align-items-center justify-content-center">
                                        <div class="customer-avatar bg-gold text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">C</div>
                                        <div class="text-start">
                                            <h6 class="mb-0 fw-bold text-white">Chandra Wijaya</h6>
                                            <span class="small text-gold">Pengguna Baru</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev" style="width: 5%;">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1) sepia(1) saturate(5) hue-rotate(5deg);"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next" style="width: 5%;">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1) sepia(1) saturate(5) hue-rotate(5deg);"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
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

                    <div class="booking-info p-5 text-white d-none d-md-flex flex-column justify-content-center position-relative" style="background: linear-gradient(135deg, rgba(26,26,26,0.92) 0%, rgba(13,13,13,0.98) 100%), url('assets/img/hero-barber.png') center/cover; flex: 1;">
                        <div class="position-relative z-2">
                            <h3 class="fw-bold mb-4">Siap Cukur <span class="text-gold">Maksimal?</span></h3>
                            <p class="mb-5 opacity-75" style="font-size: 1rem;">Amankan kursi Anda sekarang dan nikmati pengalaman grooming premium bersama barber terbaik kami.</p>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-gold-subtle p-3 rounded-circle me-3 d-flex align-items-center justify-content-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--primary-accent)" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-white">Lokasi Kami</h6>
                                    <p class="mb-0 text-gold small">Jl. Gaya Pria No. 1, Jakarta Selatan</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-gold-subtle p-3 rounded-circle me-3 d-flex align-items-center justify-content-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="var(--primary-accent)" class="bi bi-clock" viewBox="0 0 16 16">
                                      <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-white">Jam Operasional</h6>
                                    <p class="mb-0 text-gold small">Setiap Hari: 09:00 - 20:30</p>
                                </div>
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
                        <form action="" method="POST" class="booking-form" id="multiStepBookingForm">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                            
                            <!-- Stepper Header -->
                            <div class="d-flex justify-content-between mb-4 position-relative" id="bookingStepper">
                                <div class="progress position-absolute top-50 start-0 translate-middle-y w-100" style="height: 4px; z-index: 1; background-color: rgba(255,255,255,0.1);">
                                    <div class="progress-bar bg-gold" id="stepperProgress" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="step-indicator active d-flex flex-column align-items-center position-relative z-2" data-step="1">
                                    <div class="step-icon rounded-circle bg-gold text-dark d-flex align-items-center justify-content-center fw-bold shadow" style="width: 40px; height: 40px; transition: all 0.3s ease;">1</div>
                                    <span class="step-label small mt-2 text-white fw-semibold" style="transition: all 0.3s ease;">Layanan</span>
                                </div>
                                <div class="step-indicator d-flex flex-column align-items-center position-relative z-2" data-step="2">
                                    <div class="step-icon rounded-circle bg-dark text-secondary border border-secondary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; transition: all 0.3s ease;">2</div>
                                    <span class="step-label small mt-2 text-secondary" style="transition: all 0.3s ease;">Jadwal</span>
                                </div>
                                <div class="step-indicator d-flex flex-column align-items-center position-relative z-2" data-step="3">
                                    <div class="step-icon rounded-circle bg-dark text-secondary border border-secondary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; transition: all 0.3s ease;">3</div>
                                    <span class="step-label small mt-2 text-secondary" style="transition: all 0.3s ease;">Data Diri</span>
                                </div>
                            </div>

                            <!-- Step 1: Layanan -->
                            <div class="step-content active animate-fade-in" id="step1">
                                <div class="mb-4">
                                    <label class="form-label">Layanan</label>
                                    <div class="input-icon-wrapper">
                                        <div class="input-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 3.5c-.614-.884-.074-1.962.858-2.5L8 7.226 11.642 1c.932.538 1.472 1.616.858 2.5L8.81 8.61l1.556 2.661a2.5 2.5 0 1 1-.798.635L8 9.36l-1.568 2.546a2.5 2.5 0 1 1-.798-.635L7.19 8.61 3.5 3.5zm2.5 4.082l.853-1.416L5.688 4.29 4.39 6.236 6 7.582zm4 0l1.61-1.346-1.298-1.945-.853 1.416L10 7.582zM4.5 14a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm7 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg>
                                        </div>
                                        <input type="hidden" name="service_id" id="serviceInput" required>
                                        <button class="form-control text-start shadow-none w-100 d-flex align-items-center py-2" type="button" id="serviceDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" style="padding-right: 1.2rem; cursor: pointer;">
                                            <div id="serviceDropdownText" class="d-flex align-items-center justify-content-between flex-grow-1 me-2 text-start">
                                                <span class="text-white opacity-75">-- Pilih Layanan --</span>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="var(--primary-accent)" class="bi bi-chevron-down flex-shrink-0" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-dark w-100 shadow-lg border-0 rounded-4 mt-2 p-0" aria-labelledby="serviceDropdownBtn" style="background: linear-gradient(145deg, #1e1e1e 0%, #161616 100%); border: 1px solid rgba(212, 175, 55, 0.15) !important; overflow: hidden;">
                                            <ul class="list-unstyled m-0 py-2 custom-scrollbar" style="max-height: 250px; overflow-y: auto;">
                                                <?php foreach($services as $s): ?>
                                                    <li>
                                                        <a class="dropdown-item py-3 px-4 service-select-item border-bottom border-secondary border-opacity-25" href="#" data-value="<?= $s['id'] ?>" data-name="<?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8') ?>" data-price="Rp <?= number_format($s['price'] ?? 0, 0, ',', '.') ?>">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <span class="fw-semibold text-white fs-6 text-wrap pe-3" style="line-height: 1.3;"><?= htmlspecialchars($s['name']) ?></span>
                                                                <span class="text-gold fw-bold flex-shrink-0 align-self-start mt-1">Rp <?= number_format($s['price'] ?? 0, 0, ',', '.') ?></span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>

                                    </div>
                                    <div class="text-danger mt-2 small d-none fw-semibold" id="serviceError"><i class="bi bi-exclamation-circle me-1"></i>Silakan pilih layanan terlebih dahulu.</div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-gold px-4 py-2 fw-bold rounded-pill shadow btn-next">
                                        Selanjutnya
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right-short ms-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Jadwal -->
                            <div class="step-content d-none animate-fade-in" id="step2">
                                <div class="mb-4">
                                    <label for="dateInput" class="form-label">Tanggal Booking</label>
                                    <div class="input-icon-wrapper">
                                        <div class="input-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                                        </div>
                                        <input type="date" name="date" class="form-control" id="dateInput" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <div class="text-danger mt-2 small d-none fw-semibold" id="dateError"><i class="bi bi-exclamation-circle me-1"></i>Silakan pilih tanggal booking.</div>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-end mb-3">
                                        <label class="form-label mb-0">Pilih Waktu</label>
                                        <span class="badge bg-gold-subtle rounded-pill text-gold px-2 py-1" style="font-size: 0.7rem;">Live Availability</span>
                                    </div>
                                    
                                    <ul class="nav nav-pills mb-3 justify-content-between slot-pills" id="pills-tab" role="tablist">
                                        <li class="nav-item flex-fill text-center mx-1" role="presentation">
                                            <button class="nav-link w-100 active rounded-pill py-2 px-1" id="pills-pagi-tab" data-bs-toggle="pill" data-bs-target="#pills-pagi" type="button" role="tab">Pagi</button>
                                        </li>
                                        <li class="nav-item flex-fill text-center mx-1" role="presentation">
                                            <button class="nav-link w-100 rounded-pill py-2 px-1" id="pills-siang-tab" data-bs-toggle="pill" data-bs-target="#pills-siang" type="button" role="tab">Siang</button>
                                        </li>
                                        <li class="nav-item flex-fill text-center mx-1" role="presentation">
                                            <button class="nav-link w-100 rounded-pill py-2 px-1" id="pills-sore-tab" data-bs-toggle="pill" data-bs-target="#pills-sore" type="button" role="tab">Sore</button>
                                        </li>
                                        <li class="nav-item flex-fill text-center mx-1" role="presentation">
                                            <button class="nav-link w-100 rounded-pill py-2 px-1" id="pills-malam-tab" data-bs-toggle="pill" data-bs-target="#pills-malam" type="button" role="tab">Malam</button>
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
                                    <div class="text-danger mt-3 p-2 bg-danger bg-opacity-10 rounded border border-danger border-opacity-25 small d-none fw-semibold" id="timeError">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>
                                        Silakan pilih salah satu waktu yang tersedia sebelum melanjutkan.
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4 pt-2 border-top border-secondary border-opacity-25">
                                    <button type="button" class="btn btn-dark px-4 py-2 fw-bold rounded-pill border border-secondary text-white btn-prev d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg> 
                                        Kembali
                                    </button>
                                    <button type="button" class="btn btn-gold px-4 py-2 fw-bold rounded-pill shadow btn-next">
                                        Selanjutnya
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right-short ms-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Data Diri -->
                            <div class="step-content d-none animate-fade-in" id="step3">
                                <div class="mb-4">
                                    <label for="nameInput" class="form-label">Nama Lengkap</label>
                                    <div class="input-icon-wrapper">
                                        <div class="input-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/></svg>
                                        </div>
                                        <input type="text" name="name" class="form-control" id="nameInput" placeholder="Masukkan nama Anda" required>
                                    </div>
                                    <div class="invalid-feedback">Silakan masukkan nama lengkap Anda.</div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="phoneInput" class="form-label">Nomor WhatsApp</label>
                                    <div class="input-icon-wrapper">
                                        <div class="input-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c-.003 1.396.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/></svg>
                                        </div>
                                        <input type="text" name="phone" class="form-control" id="phoneInput" placeholder="Contoh: 0812345678" required>
                                    </div>
                                    <div class="invalid-feedback">Silakan masukkan nomor WhatsApp Anda.</div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-4 mt-2 p-3 rounded-3" style="background-color: rgba(255,255,255,0.02); border: 1px solid rgba(212,175,55,0.1);">
                                    <div class="bg-gold-subtle rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 36px; height: 36px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M5.338 1.59a59.768 59.768 0 0 0-2.836.856c-.99.333-1.6 1.12-1.6 2.062 0 1.84.02 4.14.1 6.131.08 2.054.49 4.135 1.76 5.568.64.71 1.48 1.16 2.5 1.34 1.15.2 2.37.2 3.65 0 1.02-.18 1.86-.63 2.5-1.34 1.27-1.433 1.68-3.514 1.76-5.568.08-1.99.1-4.291.1-6.131 0-.942-.61-1.73-1.6-2.062a59.768 59.768 0 0 0-2.836-.856C9.176 1.27 8.583 1 8 1s-1.176.27-2.662.59zM8 2.316a58.4 58.4 0 0 1 2.5.76C11.55 3.39 12 3.96 12 4.608c0 1.78-.016 3.98-.075 5.89-.06 1.9-.384 3.585-1.254 4.545-.5.54-1.09.84-1.802.99-1.04.22-2.13.22-3.17 0-.71-.15-1.3-.45-1.8-.99-.87-.96-1.19-2.64-1.25-4.54C2.016 8.58 2 6.38 2 4.608c0-.65.45-1.22 1.5-1.53a58.4 58.4 0 0 1 2.5-.76C6.88 2.115 7.42 2 8 2zm.854 4.854a.5.5 0 0 0-.708 0l-1.5 1.5a.5.5 0 0 0 .708.708l1.146-1.147 2.146 2.147a.5.5 0 0 0 .708-.708l-2.5-2.5z"/>
                                        </svg>
                                    </div>
                                    <p class="small text-gold mb-0 m-0" style="line-height: 1.4;">
                                        Dengan melakukan booking, Anda menyetujui <a href="Privacy.php" class="text-white text-decoration-none border-bottom border-gold fw-semibold">Kebijakan Privasi</a> kami.
                                    </p>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4 pt-2 border-top border-secondary border-opacity-25">
                                    <button type="button" class="btn btn-dark px-4 py-2 fw-bold rounded-pill border border-secondary text-white btn-prev d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg> 
                                        Kembali
                                    </button>
                                    <button type="submit" class="btn btn-gold px-4 py-2 fw-bold rounded-pill shadow d-flex justify-content-center align-items-center gap-2" id="submitBookingBtn">
                                        <span>Konfirmasi Booking</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                            <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                                            <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
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
                btnText.innerHTML = `
                    <span class="text-white fw-semibold text-wrap pe-2" style="line-height: 1.3;">${name}</span>
                    <span class="text-gold fw-bold flex-shrink-0 align-self-start">${price}</span>
                `;
                btnText.classList.remove('text-muted');
                document.getElementById('serviceError').classList.add('d-none');
            });
        });

        function selectService(id, name, price) {
            document.getElementById('serviceInput').value = id;
            const btnText = document.getElementById('serviceDropdownText');
            btnText.innerHTML = `
                <span class="text-white fw-semibold text-wrap pe-2" style="line-height: 1.3;">${name}</span>
                <span class="text-gold fw-bold flex-shrink-0 align-self-start">${price}</span>
            `;
            btnText.classList.remove('text-muted');
            document.getElementById('serviceError').classList.add('d-none');
        }

        // Stepper Logic
        document.addEventListener("DOMContentLoaded", function() {
            let currentStep = 1;
            const totalSteps = 3;
            
            const bookingModal = document.getElementById('bookingModal');
            if (bookingModal) {
                bookingModal.addEventListener('hidden.bs.modal', function () {
                    currentStep = 1;
                    updateStepper();
                    const errors = ['serviceError', 'dateError', 'timeError'];
                    errors.forEach(err => {
                        const el = document.getElementById(err);
                        if (el) {
                            el.classList.add('d-none');
                            el.classList.remove('d-block');
                        }
                    });
                });
            }
            
            const steps = document.querySelectorAll('.step-content');
            const indicators = document.querySelectorAll('.step-indicator');
            const progress = document.getElementById('stepperProgress');
            
            const btnNext = document.querySelectorAll('.btn-next');
            const btnPrev = document.querySelectorAll('.btn-prev');
            
            function updateStepper() {
                // Update progress bar
                const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
                progress.style.width = progressPercentage + '%';
                
                // Update step indicators
                indicators.forEach((indicator, index) => {
                    const stepNum = index + 1;
                    const icon = indicator.querySelector('.step-icon');
                    const label = indicator.querySelector('.step-label');
                    
                    if (stepNum < currentStep) {
                        // Completed steps
                        icon.className = 'step-icon rounded-circle bg-gold text-dark d-flex align-items-center justify-content-center fw-bold shadow';
                        icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/></svg>';
                        label.className = 'step-label small mt-2 text-white fw-semibold';
                    } else if (stepNum === currentStep) {
                        // Current step
                        icon.className = 'step-icon rounded-circle bg-gold text-dark d-flex align-items-center justify-content-center fw-bold shadow';
                        icon.innerHTML = stepNum;
                        label.className = 'step-label small mt-2 text-white fw-semibold';
                    } else {
                        // Future steps
                        icon.className = 'step-icon rounded-circle bg-dark text-secondary border border-secondary d-flex align-items-center justify-content-center fw-bold';
                        icon.innerHTML = stepNum;
                        label.className = 'step-label small mt-2 text-secondary';
                    }
                });
                
                // Show/hide content
                steps.forEach((step, index) => {
                    if (index + 1 === currentStep) {
                        step.classList.remove('d-none');
                        step.classList.add('active');
                    } else {
                        step.classList.add('d-none');
                        step.classList.remove('active');
                    }
                });
            }
            
            function validateStep(step) {
                let isValid = true;
                
                if (step === 1) {
                    const serviceInput = document.getElementById('serviceInput');
                    if (!serviceInput.value) {
                        document.getElementById('serviceError').classList.remove('d-none');
                        document.getElementById('serviceError').classList.add('d-block');
                        isValid = false;
                    } else {
                        document.getElementById('serviceError').classList.add('d-none');
                        document.getElementById('serviceError').classList.remove('d-block');
                    }
                } else if (step === 2) {
                    const dateInput = document.getElementById('dateInput');
                    let timeSelected = false;
                    document.querySelectorAll('input[name="time"]').forEach(radio => {
                        if(radio.checked) timeSelected = true;
                    });
                    
                    if (!dateInput.value) {
                        document.getElementById('dateError').classList.remove('d-none');
                        document.getElementById('dateError').classList.add('d-block');
                        isValid = false;
                    } else {
                        document.getElementById('dateError').classList.add('d-none');
                        document.getElementById('dateError').classList.remove('d-block');
                    }
                    
                    if (!timeSelected) {
                        document.getElementById('timeError').classList.remove('d-none');
                        document.getElementById('timeError').classList.add('d-block');
                        isValid = false;
                    } else {
                        document.getElementById('timeError').classList.add('d-none');
                        document.getElementById('timeError').classList.remove('d-block');
                    }
                } else if (step === 3) {
                    const nameInput = document.getElementById('nameInput');
                    const phoneInput = document.getElementById('phoneInput');
                    
                    if (!nameInput.value.trim()) {
                        nameInput.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        nameInput.classList.remove('is-invalid');
                    }
                    
                    if (!phoneInput.value.trim()) {
                        phoneInput.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        phoneInput.classList.remove('is-invalid');
                    }
                }
                
                return isValid;
            }
            
            btnNext.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (validateStep(currentStep)) {
                        if (currentStep < totalSteps) {
                            currentStep++;
                            updateStepper();
                        }
                    }
                });
            });
            
            btnPrev.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentStep > 1) {
                        currentStep--;
                        updateStepper();
                    }
                });
            });
            
            // Remove invalid class on input
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    if(this.name === 'time') {
                        document.getElementById('timeError').classList.add('d-none');
                        document.getElementById('timeError').classList.remove('d-block');
                    }
                });
            });

            // Date change logic that was previously inline
            document.getElementById('dateInput').addEventListener('change', function() {
                const date = this.value;
                const tabContent = document.getElementById('pills-tabContent');
                
                // Show Skeleton Loading
                const sessions = ['pagi', 'siang', 'sore', 'malam'];
                sessions.forEach(session => {
                    const container = document.querySelector(`#pills-${session} .row`);
                    if (container) {
                        container.innerHTML = '';
                        for(let i=0; i<6; i++) {
                            container.innerHTML += `
                                <div class="col-4">
                                    <div class="skeleton-box skeleton-chip"></div>
                                </div>
                            `;
                        }
                    }
                });
                
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
                        
                        // Re-attach listeners for dynamically added inputs
                        document.querySelectorAll('input[name="time"]').forEach(input => {
                            input.addEventListener('change', function() {
                                document.getElementById('timeError').classList.add('d-none');
                                document.getElementById('timeError').classList.remove('d-block');
                            });
                        });
                    });
            });
            
            // Listeners for initial radio buttons
            document.querySelectorAll('input[name="time"]').forEach(input => {
                input.addEventListener('change', function() {
                    document.getElementById('timeError').classList.add('d-none');
                    document.getElementById('timeError').classList.remove('d-block');
                });
            });

            // Form submission validation
            document.getElementById('multiStepBookingForm').addEventListener('submit', function(e) {
                if (!validateStep(3)) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>