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
                                <input type="password" name="password" class="form-control" required autocomplete="current-password">
                            </div>
                            <button type="submit" class="btn btn-dark w-100">Masuk</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
