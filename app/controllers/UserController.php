<?php

require_once './app/models/User.php';
require_once './app/helpers/SanitizationHelper.php';
require_once './app/helpers/InputHelper.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    private function checkCSRFToken($token) {
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            die('Invalid CSRF token');
        }
    }
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->checkCSRFToken($_POST['csrf_token']);
            $sanitizedData = SanitizationHelper::sanitizeArray($_POST);

            $username = $sanitizedData['username'];
            $password = $sanitizedData['password'];
            $email = SanitizationHelper::sanitizeInput($sanitizedData['email']);
            if ($this->userModel->register($username, $password, $email)) {
                header("Location: index.php?action=login");
            } else {
                echo "Registration failed";
            }
        } else {
            include_once './app/views/user/register.php';
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->checkCSRFToken($_POST['csrf_token']);
            $sanitizedData = SanitizationHelper::sanitizeArray($_POST);

            $username = SanitizationHelper::sanitizeInput($sanitizedData['username']);
            $password = SanitizationHelper::sanitizeInput($sanitizedData['password']);
            $user = $this->userModel->login($username, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php");
            } else {
                echo "Invalid credentials";
            }
        } else {
            include_once './app/views/user/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
    }
}
?>
