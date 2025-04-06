<?php
require_once __DIR__ . '/../dao/CategoryDAO.php';

class CategoryService {
    private $categoryDao;

    public function __construct() {
        $this->categoryDao = new CategoryDAO(Flight::get('pdo'));
       
    }

    public function getAllCategories() {
        return $this->categoryDao->getAll();
    }

    public function getCategoryById($id) {
        return $this->categoryDao->getById($id);
    }

    public function createCategory($data) {
        return $this->categoryDao->create($data);
    }

    public function updateCategory($id, $data) {
        return $this->categoryDao->update($id, $data);
    }

    public function deleteCategory($id) {
        return $this->categoryDao->delete($id);
    }
}