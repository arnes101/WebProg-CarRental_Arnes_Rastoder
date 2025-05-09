<?php
require_once __DIR__ . '/../dao/UserDAO.php';

class UserService {
    private $userDao;

    public function __construct() {
     $this->userDao = new UserDAO(Flight::get('pdo'));
     

    }

    public function getAllUsers() {
        return $this->userDao->getAll();
    }

    public function getUserById($id) {
        return $this->userDao->get($id);
    }

    public function createUser($data) {
        return $this->userDao->create($data);
    }

    public function updateUser($id, $data) {
        return $this->userDao->update($id, $data);
    }

    public function deleteUser($id) {
        return $this->userDao->delete($id);
    }
}
