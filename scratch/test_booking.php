<?php
require_once 'app/Controller/BookingController.php';
require_once 'app/Helpers/Csrf.php';
require_once 'app/Helpers/AppSession.php';

AppSession::start();
$token = Csrf::token();

$ctrl = new BookingController();
$data = [
    'csrf_token' => $token,
    'name' => 'Roni Test',
    'phone' => '087655444444',
    'service_id' => '1',
    'date' => date('Y-m-d'),
    'time' => '15:30',
    'notes' => 'Test notes'
];

$result = $ctrl->createBooking($data);
print_r($result);
