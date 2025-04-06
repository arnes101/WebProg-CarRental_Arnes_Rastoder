<?php
require_once __DIR__ . '/../services/ReviewService.php';

Flight::route('GET /reviews', function () {
    $service = new ReviewService();
    Flight::json($service->getAllReviews());
});

Flight::route('GET /reviews/@id', function ($id) {
    $service = new ReviewService();
    Flight::json($service->getReviewById($id));
});

Flight::route('POST /reviews', function () {
    $data = Flight::request()->data->getData();
    $service = new ReviewService();
    Flight::json($service->createReview($data));
});

Flight::route('PUT /reviews/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $service = new ReviewService();
    Flight::json($service->updateReview($id, $data));
});

Flight::route('DELETE /reviews/@id', function ($id) {
    $service = new ReviewService();
    Flight::json($service->deleteReview($id));
});
