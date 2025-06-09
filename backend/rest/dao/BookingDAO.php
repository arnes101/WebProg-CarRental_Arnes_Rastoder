<?php
class BookingDAO {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function getAll() {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($booking) {
        // Basic validation
        if (empty($booking['user_id']) || !is_numeric($booking['user_id'])) {
            throw new Exception("Invalid user ID");
        }
    
        if (empty($booking['car_id']) || !is_numeric($booking['car_id'])) {
            throw new Exception("Invalid car ID");
        }
    
        if (empty($booking['rental_date'])) {
            throw new Exception("Rental date is required");
        }
    
        if (empty($booking['pickup_location']) || strlen(trim($booking['pickup_location'])) < 3) {
            throw new Exception("Pickup location must be at least 3 characters");
        }
    
        if (empty($booking['phone_number']) || !preg_match('/^\+?\d{7,15}$/', $booking['phone_number'])) {
            throw new Exception("Invalid phone number format");
        }
    
        // Sanitize
        $pickup_location = htmlspecialchars(trim($booking['pickup_location']));
        $phone_number = htmlspecialchars(trim($booking['phone_number']));
    
        // Insert
        $stmt = $this->pdo->prepare("INSERT INTO bookings (user_id, car_id, rental_date, pickup_location, phone_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $booking['user_id'],
            $booking['car_id'],
            $booking['rental_date'],
            $pickup_location,
            $phone_number
        ]);
    
        return $this->pdo->lastInsertId();
    }
    

    public function update($id, $booking) {
        $stmt = $this->pdo->prepare("UPDATE bookings SET user_id = ?, car_id = ?, rental_date = ?, pickup_location = ?, phone_number = ? WHERE id = ?");
        $stmt->execute([$booking['user_id'], $booking['car_id'], $booking['rental_date'], $booking['pickup_location'], $booking['phone_number'], $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}