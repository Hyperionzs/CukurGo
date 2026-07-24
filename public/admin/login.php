<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Config/Database.php';
require_once __DIR__ . '/../../app/Helpers/AdminAuth.php';
require_once __DIR__ . '/../../app/Helpers/Csrf.php';
require_once __DIR__ . '/../../app/Helpers/Layout.php';

new Database();

AdminAuth::assertIpAllowlist();
AdminAuth::assertHttpBasicIfConfigured();

if (AdminAuth::isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
        $error = 'Sesi tidak valid. Muat ulang halaman.';
    } elseif (!AdminAuth::passwordHashConfigured()) {
        $error = 'Konfigurasi belum lengkap: set ADMIN_PASSWORD_HASH di file .env (lihat .env.example).';
    } elseif (AdminAuth::verifyPassword($_POST['password'] ?? '')) {
        AdminAuth::loginSuccess();
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Password salah.';
    }
}

$csrf = Csrf::token();

layoutRenderHead([
    'title' => 'Login Admin - CukurGo',
    'description' => 'Masuk ke panel admin CukurGo untuk memantau reservasi.',
    'asset_prefix' => '../',
]);
?>
<body class="bg-dark text-white" style="min-height: 100vh; background: radial-gradient(circle at top right, #1a1a1a, #000000);">
    <!-- Dotted overlay -->
    <div style="position:fixed;top:0;left:0;width:100%;height:100%;background-image:radial-gradient(rgba(255,255,255,0.1) 1px,transparent 1px);background-size:20px 20px;pointer-events:none;opacity:0.15;z-index:0;"></div>

    <div class="d-flex align-items-center justify-content-center position-relative" style="min-height: 100vh; z-index: 1;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-11 col-sm-8 col-md-5 col-lg-4">
                    
                    <!-- Logo -->
                    <div class="text-center mb-4 animate-slide-up">
                        <a href="../Index.php" class="text-decoration-none">
                            <h1 class="text-gold mb-1" style="font-family: 'Lobster', cursive; font-size: 3rem;">CukurGo</h1>
                        </a>
                        <p class="text-light opacity-50 small text-uppercase fw-semibold" style="letter-spacing: 3px;">Admin Panel</p>
                    </div>

                    <!-- Login Card -->
                    <div class="animate-slide-up-delay" style="animation-delay: 0.15s;">
                        <div class="rounded-4 p-4 p-md-5 position-relative overflow-hidden" style="background: linear-gradient(145deg, rgba(30,30,30,0.9) 0%, rgba(18,18,18,0.95) 100%); border: 1px solid rgba(212, 175, 55, 0.15); backdrop-filter: blur(20px);">
                            
                            <!-- Glow effect -->
                            <div class="position-absolute" style="top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(212,175,55,0.08); filter: blur(60px); pointer-events: none;"></div>
                            <div class="position-absolute" style="bottom: -50px; left: -50px; width: 150px; height: 150px; background: rgba(212,175,55,0.05); filter: blur(50px); pointer-events: none;"></div>

                            <!-- Lock Icon -->
                            <div class="text-center mb-4 position-relative z-1">
                                <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle shadow" style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--primary-accent) 0%, #b8962e 100%);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#000" viewBox="0 0 16 16">
                                        <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                                    </svg>
                                </div>
                            </div>

                            <h4 class="text-center fw-bold mb-1 text-white position-relative z-1" style="font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;">Masuk Admin</h4>
                            <p class="text-center text-light opacity-50 small mb-4 position-relative z-1">Masukkan password admin untuk melanjutkan</p>

                            <?php if ($error !== ''): ?>
                                <div class="alert border-0 shadow-sm mb-4 rounded-3 d-flex align-items-center position-relative z-1" style="background-color: rgba(255, 77, 77, 0.1); color: #ff6b6b; border: 1px solid rgba(255, 77, 77, 0.2) !important;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2 flex-shrink-0" viewBox="0 0 16 16">
                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </svg>
                                    <span class="small fw-semibold"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            <?php endif; ?>

                            <form method="post" action="" class="position-relative z-1">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
                                
                                <div class="mb-4">
                                    <label class="form-label small fw-semibold" style="color: var(--primary-accent);">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-0" style="background: rgba(255,255,255,0.03); border-right: none !important;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="var(--primary-accent)" viewBox="0 0 16 16">
                                                <path d="M5.338 1.59a59.768 59.768 0 0 0-2.836.856c-.99.333-1.6 1.12-1.6 2.062 0 1.84.02 4.14.1 6.131.08 2.054.49 4.135 1.76 5.568.64.71 1.48 1.16 2.5 1.34 1.15.2 2.37.2 3.65 0 1.02-.18 1.86-.63 2.5-1.34 1.27-1.433 1.68-3.514 1.76-5.568.08-1.99.1-4.291.1-6.131 0-.942-.61-1.73-1.6-2.062a59.768 59.768 0 0 0-2.836-.856C9.176 1.27 8.583 1 8 1s-1.176.27-2.662.59z"/>
                                            </svg>
                                        </span>
                                        <input id="admin-password" type="password" name="password" class="form-control border-0 shadow-none" required autocomplete="current-password" placeholder="Masukkan password..." style="background: rgba(255,255,255,0.03); color: #fff; border-radius: 0; padding: 14px 12px;">
                                        <button id="toggle-password" class="input-group-text border-0" type="button" aria-label="Tampilkan password" aria-pressed="false" style="background: rgba(255,255,255,0.03); cursor: pointer; transition: all 0.3s ease;">
                                            <span id="icon-eye-open" aria-hidden="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#a0a0a0" viewBox="0 0 16 16">
                                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8Zm-8 4.5A4.5 4.5 0 1 1 8 3.5a4.5 4.5 0 0 1 0 9Z"/>
                                                    <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"/>
                                                </svg>
                                            </span>
                                            <span id="icon-eye-closed" class="d-none" aria-hidden="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="var(--primary-accent)" viewBox="0 0 16 16">
                                                    <path d="M13.359 11.238C14.463 10.12 15.232 8.73 16 8c-1.333-2.333-4.333-5.5-8-5.5a8.67 8.67 0 0 0-2.868.496l1.282 1.282A5.19 5.19 0 0 1 8 4.5c2.7 0 5.046 1.91 6.542 3.5-.466.494-1.058 1.056-1.757 1.57l.574.668ZM11.297 9.176a3 3 0 0 0-3.473-3.473l3.473 3.473Zm-5.63-2.157A3 3 0 0 0 8.98 10.333L5.667 7.019Z"/>
                                                    <path d="m2.354 1.646 12 12-.708.708-12-12 .708-.708Z"/>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                    <div style="height: 2px; background: linear-gradient(90deg, var(--primary-accent), transparent); border-radius: 2px; margin-top: 0;"></div>
                                </div>

                                <button type="submit" class="btn w-100 fw-bold rounded-pill py-3 shadow" style="background: linear-gradient(135deg, var(--primary-accent) 0%, #b8962e 100%); color: #000; font-size: 1rem; letter-spacing: 0.5px; transition: all 0.3s ease; border: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                                        <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"/>
                                        <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM6.5 7a1 1 0 1 1 0 2 1 1 0 0 1 0-2Zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2Zm-4 3.5a1 1 0 1 1 0 2 1 1 0 0 1 0-2Zm4 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2Z"/>
                                    </svg>
                                    Masuk ke Dashboard
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Footer link -->
                    <div class="text-center mt-4 animate-slide-up-delay-2" style="animation-delay: 0.3s;">
                        <a href="../Index.php" class="text-gold text-decoration-none small fw-semibold d-inline-flex align-items-center gap-2" style="transition: all 0.3s ease;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                            </svg>
                            Kembali ke Halaman Utama
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var passwordInput = document.getElementById('admin-password');
            var toggleButton = document.getElementById('toggle-password');
            var iconOpen = document.getElementById('icon-eye-open');
            var iconClosed = document.getElementById('icon-eye-closed');
            if (!passwordInput || !toggleButton || !iconOpen || !iconClosed) {
                return;
            }
            toggleButton.addEventListener('click', function () {
                var isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                toggleButton.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                toggleButton.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
                iconOpen.classList.toggle('d-none', isHidden);
                iconClosed.classList.toggle('d-none', !isHidden);
            });

            // Efek hover tombol submit
            var submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 10px 30px rgba(212, 175, 55, 0.3)';
                });
                submitBtn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            }
        })();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
