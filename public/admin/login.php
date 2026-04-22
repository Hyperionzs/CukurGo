<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Config/Database.php';
require_once __DIR__ . '/../../app/Helpers/AdminAuth.php';
require_once __DIR__ . '/../../app/Helpers/Csrf.php';

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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - CukurGo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <strong>CukurGo Admin</strong>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error !== ''): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                        <form method="post" action="">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input id="admin-password" type="password" name="password" class="form-control" required autocomplete="current-password">
                                    <button id="toggle-password" class="btn btn-outline-secondary" type="button" aria-label="Tampilkan password" aria-pressed="false">
                                        <span id="icon-eye-open" aria-hidden="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8Zm-8 4.5A4.5 4.5 0 1 1 8 3.5a4.5 4.5 0 0 1 0 9Z"/>
                                                <path d="M8 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"/>
                                            </svg>
                                        </span>
                                        <span id="icon-eye-closed" class="d-none" aria-hidden="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M13.359 11.238C14.463 10.12 15.232 8.73 16 8c-1.333-2.333-4.333-5.5-8-5.5a8.67 8.67 0 0 0-2.868.496l1.282 1.282A5.19 5.19 0 0 1 8 4.5c2.7 0 5.046 1.91 6.542 3.5-.466.494-1.058 1.056-1.757 1.57l.574.668ZM11.297 9.176a3 3 0 0 0-3.473-3.473l3.473 3.473Zm-5.63-2.157A3 3 0 0 0 8.98 10.333L5.667 7.019Z"/>
                                                <path d="m2.354 1.646 12 12-.708.708-12-12 .708-.708Z"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark w-100">Masuk</button>
                        </form>
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
        })();
    </script>
</body>
</html>
