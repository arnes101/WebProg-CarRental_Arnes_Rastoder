<?php
require_once __DIR__ . '/../dao/ReviewDAO.php';

class ReviewService {
    private $reviewDao;

    public function __construct() {
        $this->reviewDao = new ReviewDAO(Flight::get('pdo'));
    }

    public function getAllReviews() {
        return $this->reviewDao->getAll();
    }

    public function getReviewById($id) {
        return $this->reviewDao->getById($id);
    }

    public function createReview($data) {
        return $this->reviewDao->create($data);
    }

    public function updateReview($id, $data) {
        return $this->reviewDao->update($id, $data);
    }

    public function deleteReview($id) {
        return $this->reviewDao->delete($id);
    }
}