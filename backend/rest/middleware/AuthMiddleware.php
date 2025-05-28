<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    public function verifyToken() {
        $headers = getallheaders();
        file_put_contents("debug.log", "🔐 Headers: " . json_encode($headers) . "\n", FILE_APPEND);
    
        if (!isset($headers['Authorization'])) {
            Flight::halt(401, json_encode(["error" => "Token required"]));
        }
    
        $token = str_replace("Bearer ", "", $headers['Authorization']);
    
        try {
            $decoded = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
Flight::set('user', $decoded->user); // ✅ Set actual user object

        } catch (Exception $e) {
            file_put_contents("debug.log", "❌ JWT Decode Error: " . $e->getMessage() . "\n", FILE_APPEND);
            Flight::halt(401, json_encode(["error" => "Invalid token"]));
        }
    }
    

    public function authorizeRole($role) {
        $user = Flight::get('user');
        if ($user->role !== $role) {
            Flight::halt(403, json_encode(["error" => "Forbidden"]));
        }
    }

    public function authorizeRoles($roles) {
        $user = Flight::get('user');
        if (!in_array($user->role, $roles)) {
            Flight::halt(403, json_encode(["error" => "Forbidden"]));
        }
    }
}
