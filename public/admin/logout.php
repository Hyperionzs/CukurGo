<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Config/Database.php';
require_once __DIR__ . '/../../app/Helpers/AdminAuth.php';

new Database();

AdminAuth::logout();
if (AdminAuth::basicAuthEnabled()) {
    AdminAuth::sendBasicLogoutChallenge();
}
header('Location: login.php');
exit;
