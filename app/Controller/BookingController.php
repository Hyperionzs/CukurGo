<?php

require_once __DIR__ . '/../Config/Database.php';

class BookingController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function createBooking($data) {
        $query = "INSERT INTO reservations (customer_name, phone_number, service_id, reservation_date, reservation_time) 
                  VALUES (:name, :phone, :service, :date, :time)";
        
        $stmt = $this->db->prepare($query);
        
        // Sanitize data
        $name = htmlspecialchars(strip_tags($data['name']));
        
        return $stmt->execute([
            ':name' => $name,
            ':phone' => $data['phone'],
            ':service' => $data['service_id'],
            ':date' => $data['date'],
            ':time' => $data['time']
        ]);
    }
}