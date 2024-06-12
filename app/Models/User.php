<?php
namespace App\Models;

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
    public function getUserSettings($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM user_settings WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function saveUserSettings($user_id, $dark_mode, $delete_confirmation) {
        $stmt = $this->db->prepare("INSERT INTO user_settings (user_id, dark_mode, delete_confirmation) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE dark_mode = VALUES(dark_mode), delete_confirmation = VALUES(delete_confirmation)");
        $stmt->bind_param("iii", $user_id, $dark_mode, $delete_confirmation);
        return $stmt->execute();
    }
}
?>
