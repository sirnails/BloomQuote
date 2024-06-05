<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;


require_once './config/database.php';

class UserControllerTest extends TestCase
{
    protected $userController;

    public function testTrueIsTrue()
    {
        $this->assertTrue(true, 'True is not true');
    }

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

        // Check if the user is in the database
        $db = db_connect();
        $stmt = $db->prepare("SELECT id, username, email FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $username = 'testuser';
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $retrievedUsername, $retrievedEmail);

        // Verify that one record was found
        $this->assertEquals(1, $stmt->num_rows, 'Expected one user to be found in the database.');

        // Fetch the result and verify the details
        if ($stmt->num_rows === 1) {
            $stmt->fetch();
            $this->assertEquals('testuser', $retrievedUsername);
            $this->assertEquals('testuser@example.com', $retrievedEmail);
        }

        $stmt->close();
        $db->close();
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
