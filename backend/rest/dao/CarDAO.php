
<?php
class CarDAO {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM cars");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO cars (name, price_per_day, kilometers, fuel_consumption, category_id, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['price_per_day'], $data['kilometers'], $data['fuel_consumption'], $data['category_id'], $data['image_url']]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE cars SET name = ?, price_per_day = ?, kilometers = ?, fuel_consumption = ?, category_id = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['price_per_day'], $data['kilometers'], $data['fuel_consumption'], $data['category_id'], $data['image_url'], $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}