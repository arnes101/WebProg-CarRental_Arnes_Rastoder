<?php
require_once __DIR__ . '/../dao/BookingDAO.php';

class BookingService {
    private $bookingDao;

    public function __construct() {
        $this->bookingDao = new BookingDAO(Flight::get('pdo'));
    }

    public function getAllBookings() {
        return $this->bookingDao->getAll();
    }

    public function getBookingById($id) {
        return $this->bookingDao->getById($id);
    }

    public function createBooking($data) {
        return $this->bookingDao->create($data);
    }

    public function updateBooking($id, $data) {
        return $this->bookingDao->update($id, $data);
    }

    public function deleteBooking($id) {
        return $this->bookingDao->delete($id);
    }
}