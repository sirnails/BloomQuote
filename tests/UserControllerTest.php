<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;
use App\Models\User;

require_once './config/database.php';

class UserControllerTest extends TestCase
{
    protected $userController;

    protected function setUp(): void
    {
        $this->userController = new UserController();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
    }

    protected function tearDown(): void
    {
        // Clean up: delete the test user from the database
        $this->deleteTestUser();
    }

    public function testRegister()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'csrf_token' => $_SESSION['csrf_token'],
            'username' => 'testuser',
            'password' => 'password123',
            'email' => 'testuser@example.com'
        ];

        ob_start(); // Start output buffering
        $this->userController->register();
        ob_end_clean(); // Clean the output buffer

        $this->assertEquals(200, http_response_code());
    }

    private function deleteTestUser()
    {
        $db = db_connect();
        $stmt = $db->prepare("DELETE FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $username = 'testuser';
        $stmt->execute();
        $stmt->close();
        $db->close();
    }
}
