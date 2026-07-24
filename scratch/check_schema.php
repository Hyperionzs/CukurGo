<?php
require_once 'app/Config/Database.php';
$db = (new Database())->getConnection();
$stmt = $db->query("DESCRIBE reservations");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
