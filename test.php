<?php
// Simulate GET request to get session and CSRF token
$ch = curl_init('http://localhost:8000/Index.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$response = curl_exec($ch);
curl_close($ch);

if (preg_match('/name="csrf_token" value="(.*?)"/', $response, $matches)) {
    $csrf = $matches[1];
    echo "Found CSRF: $csrf\n";
    
    // Simulate POST
    $postData = [
        'csrf_token' => $csrf,
        'name' => 'John Doe',
        'phone' => '081234567890',
        'service_id' => '1',
        'date' => date('Y-m-d'),
        'time' => '10:00'
    ];
    
    $ch2 = curl_init('http://localhost:8000/Index.php');
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_POST, true);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch2, CURLOPT_COOKIEFILE, 'cookies.txt');
    $postResponse = curl_exec($ch2);
    
    // Check if there is an error message
    if (strpos($postResponse, 'Booking gagal') !== false) {
        echo "Error found in POST response!\n";
        preg_match('/<div class="alert alert-danger[^>]*>(.*?)<\/div>/s', $postResponse, $errMatch);
        echo strip_tags($errMatch[1] ?? 'No error text');
    } else {
        echo "Success, no booking gagal message!\n";
    }
} else {
    echo "No CSRF token found in response.\n";
}
