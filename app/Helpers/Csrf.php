<?php

require_once __DIR__ . '/AppSession.php';

class Csrf
{
    public static function token(): string
    {
        AppSession::start();
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function validate(?string $token): bool
    {
        AppSession::start();
        if ($token === null || $token === '' || empty($_SESSION['_csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }
}
