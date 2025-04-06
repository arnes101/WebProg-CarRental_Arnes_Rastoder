<?php
require_once __DIR__ . '/../services/BookingService.php';

Flight::route('GET /bookings', function () {
    $service = new BookingService();
    Flight::json($service->getAllBookings());
});

Flight::route('GET /bookings/@id', function ($id) {
    $service = new BookingService();
    Flight::json($service->getBookingById($id));
});

Flight::route('POST /bookings', function () {
    $data = Flight::request()->data->getData();
    $service = new BookingService();
    Flight::json($service->createBooking($data));
});

Flight::route('PUT /bookings/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $service = new BookingService();
    Flight::json($service->updateBooking($id, $data));
});

Flight::route('DELETE /bookings/@id', function ($id) {
    $service = new BookingService();
    Flight::json($service->deleteBooking($id));
});