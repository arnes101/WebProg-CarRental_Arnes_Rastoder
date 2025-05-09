<?php
class ReviewDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM reviews");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($review) {
        $stmt = $this->pdo->prepare("INSERT INTO reviews (user_id, car_id, content, created_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $review['user_id'], 
            $review['car_id'], 
            $review['content'],
            $review['created_at'] ?? date('Y-m-d H:i:s') 
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $review) {
        $stmt = $this->pdo->prepare("UPDATE reviews SET content = ?, created_at = ? WHERE id = ?");
        $stmt->execute([
            $review['content'],
            $review['created_at'] ?? date('Y-m-d H:i:s'), 
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
