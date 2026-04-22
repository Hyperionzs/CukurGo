<?php

require_once __DIR__ . '/../Config/Database.php';

class BookingController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function createBooking($data) {
        $checkQuery = "SELECT id FROM reservations WHERE reservation_date = :date AND reservation_time = :time AND status != 'cancelled' LIMIT 1";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->execute([
            ':date' => $data['date'],
            ':time' => $data['time']
        ]);
        if ($checkStmt->rowCount() > 0) {
            return "CLASH";
        }
        
        $query = "INSERT INTO reservations (customer_name, phone_number, service_id, reservation_date, reservation_time) 
                  VALUES (:name, :phone, :service, :date, :time)";
        
        $stmt = $this->db->prepare($query);
        
        // Sanitize data
        $name = htmlspecialchars(strip_tags($data['name']));
        
        try {
            $stmt->execute([
            ':name' => $name,
            ':phone' => $data['phone'],
            ':service' => $data['service_id'],
            ':date' => $data['date'],
            ':time' => $data['time']
        ]);
        return "SUCCESS";
        } catch (PDOException $e) {
            return "ERROR: Gagal membuat booking";
        }
    }
}