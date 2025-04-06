<?php
require_once __DIR__ . '/../dao/CarDAO.php';

class CarService {
    private $carDao;

    public function __construct() {
        $this->carDao = new CarDAO(Flight::get('pdo')); 
    }

    public function getAllCars() {
        return $this->carDao->getAll();
    }

    public function getCarById($id) {
        return $this->carDao->get($id);
    }

    public function createCar($data) {
        return $this->carDao->create($data);
    }

    public function updateCar($id, $data) {
        return $this->carDao->update($id, $data);
    }

    public function deleteCar($id) {
        return $this->carDao->delete($id);
    }
}