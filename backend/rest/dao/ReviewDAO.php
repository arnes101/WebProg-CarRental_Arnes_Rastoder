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
        if (empty($review['user_id']) || !is_numeric($review['user_id'])) {
            throw new Exception("Invalid user ID");
        }
        if (empty($review['car_id']) || !is_numeric($review['car_id'])) {
            throw new Exception("Invalid car ID");
        }
        if (empty($review['content']) || strlen(trim($review['content'])) < 5) {
            throw new Exception("Review content must be at least 5 characters");
        }
    
        $content = htmlspecialchars(trim($review['content']));
        $created_at = $review['created_at'] ?? date('Y-m-d H:i:s');
    
        $stmt = $this->pdo->prepare("INSERT INTO reviews (user_id, car_id, content, created_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $review['user_id'],
            $review['car_id'],
            $content,
            $created_at
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
