<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;


require_once './config/database.php';

class UserControllerTest extends TestCase
{
    protected $userController;

    protected function setUp(): void
    {
        //$this->userController = new UserController();
        //$_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
    }

    protected function tearDown(): void
    {
        // Clean up: delete the test user from the database
        //$this->deleteTestUser();
    }

    public function testRegister()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    private function deleteTestUser()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testRegisterUser()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testLoginUser()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testGetUserInfo()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testLogoutUser()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }
}
?>