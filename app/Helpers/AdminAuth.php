<?php

require_once __DIR__ . '/AppSession.php';

class AdminAuth
{
    private const SESSION_KEY = 'cukurgo_admin_ok';
    private const BASIC_REALM = 'CukurGo Admin';

    public static function env(string $key, ?string $default = null): ?string
    {
        $v = $_ENV[$key] ?? getenv($key);
        if ($v === false || $v === '') {
            return $default;
        }
        return $v;
    }

    public static function clientIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }

    /**
     * If ADMIN_ALLOWED_IPS is set (comma-separated), only those IPs may access admin.
     */
    public static function assertIpAllowlist(): void
    {
        $raw = self::env('ADMIN_ALLOWED_IPS');
        if ($raw === null || trim($raw) === '') {
            return;
        }
        $allowed = array_filter(array_map('trim', explode(',', $raw)));
        if ($allowed === []) {
            return;
        }
        $ip = self::clientIp();
        if ($ip === '' || !in_array($ip, $allowed, true)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }

    /**
     * Optional extra gate: set ADMIN_BASIC_USER and ADMIN_BASIC_PASS in .env
     */
    public static function assertHttpBasicIfConfigured(): void
    {
        if (!self::basicAuthEnabled()) {
            return;
        }
        $user = (string) self::env('ADMIN_BASIC_USER', '');
        $pass = (string) self::env('ADMIN_BASIC_PASS', '');
        $u = $_SERVER['PHP_AUTH_USER'] ?? '';
        $p = $_SERVER['PHP_AUTH_PW'] ?? '';
        if (!hash_equals($user, $u) || !hash_equals($pass, $p)) {
            header('WWW-Authenticate: Basic realm="' . self::basicRealm() . '"');
            http_response_code(401);
            echo 'Unauthorized';
            exit;
        }
    }

    public static function basicAuthEnabled(): bool
    {
        $user = self::env('ADMIN_BASIC_USER');
        $pass = self::env('ADMIN_BASIC_PASS');
        return $user !== null && $user !== '' && $pass !== null && $pass !== '';
    }

    public static function basicRealm(): string
    {
        $realm = self::env('ADMIN_BASIC_REALM', self::BASIC_REALM);
        if ($realm === null || trim($realm) === '') {
            return self::BASIC_REALM;
        }
        return trim($realm);
    }

    public static function sendBasicLogoutChallenge(): void
    {
        self::sendNoCacheHeaders();
        header('WWW-Authenticate: Basic realm="' . self::basicRealm() . '"');
        http_response_code(401);
        echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Logout Admin</title></head><body>';
        echo '<h3>Logout admin selesai.</h3>';
        echo '<p>Untuk menghapus Basic Auth yang tersimpan browser, tekan <strong>Cancel</strong> pada popup login jika muncul.</p>';
        echo '<p><a href="login.php">Kembali ke login</a></p>';
        echo '</body></html>';
        exit;
    }

    private static function sendNoCacheHeaders(): void
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    public static function isLoggedIn(): bool
    {
        AppSession::start();
        return !empty($_SESSION[self::SESSION_KEY]);
    }

    public static function loginSuccess(): void
    {
        AppSession::start();
        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = true;
    }

    public static function logout(): void
    {
        AppSession::start();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public static function requireAdmin(): void
    {
        self::assertIpAllowlist();
        self::assertHttpBasicIfConfigured();
        AppSession::start();
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit;
        }
    }

    public static function passwordHashConfigured(): bool
    {
        $h = self::env('ADMIN_PASSWORD_HASH');
        return $h !== null && $h !== '';
    }

    public static function verifyPassword(string $plain): bool
    {
        $hash = self::env('ADMIN_PASSWORD_HASH');
        if ($hash === null || $hash === '') {
            return false;
        }
        return password_verify($plain, $hash);
    }
}
