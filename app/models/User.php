<?php
namespace App\Models;

try {
    // require_once './config/database.php';
    // require_once './app/controllers/UserController.php';
    // require_once './app/controllers/QuoteController.php';
    // require_once './app/helpers/InputHelper.php';
    // require_once './app/models/User.php'; // Add this line
    // require_once './app/models/Quote.php'; // If not already included
    // require_once './app/models/QuoteItem.php'; // If not already included
} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
}

class User {
    private $db;

    public function __construct() {
        $this->db = \db_connect();
    }

    public function register($username, $password, $email) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $email);
        return $stmt->execute();
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result && password_verify($password, $result['password'])) {
            return $result;
        }
        return false;
    }

    public function getUserById($user_id) {
        $stmt = $this->db->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
