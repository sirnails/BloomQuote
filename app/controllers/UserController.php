<?php

require_once './app/models/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
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
            $username = $_POST['username'];
            $password = $_POST['password'];
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
