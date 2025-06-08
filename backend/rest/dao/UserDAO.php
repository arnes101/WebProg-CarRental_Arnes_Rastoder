<?php
class UserDAO {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT id, name, email, role FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $role = (!isset($data['role']) || empty($data['role'])) ? 'user' : $data['role'];
    
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['password'],
            $role
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        // Fetch existing user data
        $existingUser = $this->get($id);
        if (!$existingUser) {
            throw new Exception("User not found.");
        }
    
        // Merge existing data with new data
        $name = isset($data['name']) ? $data['name'] : $existingUser['name'];
        $email = isset($data['email']) ? $data['email'] : $existingUser['email'];
        $role = isset($data['role']) ? $data['role'] : $existingUser['role'];
    
        // Update query
        $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
