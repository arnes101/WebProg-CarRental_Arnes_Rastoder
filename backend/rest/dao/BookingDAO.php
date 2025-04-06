<?php
class BookingDAO {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM bookings");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($booking) {
        $stmt = $this->pdo->prepare("INSERT INTO bookings (user_id, car_id, rental_date, pickup_location, phone_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$booking['user_id'], $booking['car_id'], $booking['rental_date'], $booking['pickup_location'], $booking['phone_number']]);
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