<?php

namespace Tests;

require_once './config/database.php';

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        $this->user = new User();
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

    public function testGetUserById()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }
}
?>