
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

   public function get($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function create($data) {
    if (empty($data['name']) || strlen(trim($data['name'])) < 3) {
        throw new Exception("Car name must be at least 3 characters long.");
    }
    if (empty($data['price_per_day']) || !is_numeric($data['price_per_day'])) {
        throw new Exception("Invalid price per day");
    }
    if (empty($data['kilometers']) || !is_numeric($data['kilometers'])) {
        throw new Exception("Invalid kilometers");
    }
    if (empty($data['fuel_consumption']) || !is_numeric($data['fuel_consumption'])) {
        throw new Exception("Invalid fuel consumption");
    }
    if (empty($data['category_id']) || !is_numeric($data['category_id'])) {
        throw new Exception("Invalid category ID");
    }
    if (empty($data['image_url']) || strlen(trim($data['image_url'])) < 5) {
        throw new Exception("Invalid image URL");
    }

    $name = htmlspecialchars(trim($data['name']));
    $image_url = htmlspecialchars(trim($data['image_url']));

    $stmt = $this->pdo->prepare("INSERT INTO cars (name, price_per_day, kilometers, fuel_consumption, category_id, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $name,
        $data['price_per_day'],
        $data['kilometers'],
        $data['fuel_consumption'],
        $data['category_id'],
        $image_url
    ]);

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