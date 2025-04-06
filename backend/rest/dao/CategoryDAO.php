<?php
class CategoryDAO {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($category) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$category['name']]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $category) {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->execute([$category['name'], $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}