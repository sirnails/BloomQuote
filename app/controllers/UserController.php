<?php

namespace App\Controllers;

use App\Models;
use App\Models\User;
use App\Helpers\InputHelper;

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
            $sanitizedData = InputHelper::sanitizeArray($_POST);
    
            $username = $sanitizedData['username'];
            $password = $sanitizedData['password'];
            $email = InputHelper::sanitizeEmail($sanitizedData['email']);
    
            if (!$email) {
                echo "Invalid email address. Please provide a valid email.";
                http_response_code(400); // Bad Request
                //include_once './app/views/user/register.php';
                return;
            }
    
            if ($this->userModel->register($username, $password, $email)) {
                // Log the user in automatically after successful registration
                $user = $this->userModel->login($username, $password);
                if ($user) {
                    //TODO: after registration, redirect to the view_quotes page
                    // at the moment, it goes to a blank screen, no idea why!
                    $_SESSION['user_id'] = $user['id'];
                    header("Location: index.php?action=view_quotes");
                    http_response_code(200);
                    exit; // Ensure script stops after header redirection
                } else {
                    echo "Login after registration failed";
                    http_response_code(500); 
                }
            } else {
                echo "Registration failed";
                http_response_code(500); 
            }
        } else {
            include_once './app/views/user/register.php';
        }
    }
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->checkCSRFToken($_POST['csrf_token']);
            $sanitizedData = InputHelper::sanitizeArray($_POST);

            $username = InputHelper::sanitizeString($sanitizedData['username']);
            $password = InputHelper::sanitizeString($sanitizedData['password']);
            $user = $this->userModel->login($username, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php");
            } else {
                //TODO: Email reminder for forgotten password
                echo "Invalid credentials";
            }
        } else {
            include_once './app/views/user/login.php';
        }
    }
    public function getUserInfo($user_id) {
        return $this->userModel->getUserById($user_id);
    }
    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
    }
    public function getUserSettings($user_id) {
        return $this->userModel->getUserSettings($user_id);
    }
    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->checkCSRFToken($_POST['csrf_token']);
            $user_id = $_SESSION['user_id'];
            $dark_mode = isset($_POST['dark_mode']) ? 1 : 0;
            $delete_confirmation = isset($_POST['delete_confirmation']) ? 1 : 0;
    
            $this->userModel->saveUserSettings($user_id, $dark_mode, $delete_confirmation);
            header("Location: index.php?action=settings");
        } else {
            $user_id = $_SESSION['user_id'];
            $settings = $this->userModel->getUserSettings($user_id);
            if (!$settings) {
                $settings = [
                    'dark_mode' => 0,
                    'delete_confirmation' => 1
                ];
            }
            include_once './app/views/user/settings.php';
        }
    }
    
}
?>