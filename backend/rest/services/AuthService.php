<?php
require_once __DIR__ . '/../DAO/UserDAO.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService {
    private $userDao;

    public function __construct() {
        $this->userDao = new UserDAO(Flight::get('pdo')); // ✅ pass PDO
    }

    public function register($data) {
        file_put_contents('debug.log', "Entered register() with: " . json_encode($data) . PHP_EOL, FILE_APPEND);
    
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            file_put_contents('debug.log', "Missing required fields" . PHP_EOL, FILE_APPEND);
            throw new Exception("Missing required fields");
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
    
        // Optional: check password length
        if (strlen($data['password']) < 6) {
            throw new Exception("Password must be at least 6 characters");
        }
    
        $user = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => (isset($data['role']) && in_array($data['role'], ['admin', 'client'])) ? $data['role'] : 'client'


        ];
    
        $id = $this->userDao->create($user);
        $result = [
            "id" => $id,
            "name" => $user["name"],
            "email" => $user["email"],
            "role" => $user["role"]
        ];
        

    
        file_put_contents('debug.log', "Created user: " . json_encode($result) . PHP_EOL, FILE_APPEND);
        return $result;
    }
    
    
    public function login($data) {
        try {
            file_put_contents('debug.log', "Login data: " . json_encode($data) . "\n", FILE_APPEND);
            
            $user = $this->findUserByEmail($data['email']);
    
            if (!$user || !password_verify($data['password'], $user['password'])) {
                throw new Exception("Invalid credentials");
            }
    
            $payload = [
                'user' => [   // <--- wrap in user
                    'id' => $user['id'],
                    'role' => $user['role'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ],
                'exp' => time() + 36000
            ];
            
            $jwt = JWT::encode($payload, Config::JWT_SECRET(), 'HS256');
    
            file_put_contents('debug.log', "Token generated: $jwt\n", FILE_APPEND);
    
            return ['token' => $jwt];
        } catch (Exception $e) {
            file_put_contents('debug.log', "Login failed: " . $e->getMessage() . "\n", FILE_APPEND);
            throw $e;
        }
    }
    

    private function findUserByEmail($email) {
        $stmt = Flight::get('pdo')->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
