<?php
namespace Tests;
require_once 'config/database.php'; // Include database configuration

use PHPUnit\Framework\TestCase;
use App\Controllers\UserController;

class UserControllerClassExistsTest extends TestCase
{

    public function testTrueIsTrue()
    {
        $this->assertTrue(true, 'True is not true');
    }

    public function testUserControllerClassExists()
    {
        // Ensure the class exists
        $this->assertTrue(class_exists('App\Controllers\UserController'), 'Class App\Controllers\UserController does not exist');
        
        // Instantiate the UserController class
        $userController = new UserController();
        
        // Check if the instantiated object is of the correct type
        $this->assertInstanceOf(UserController::class, $userController, 'The instantiated object is not of type UserController');
    }

    public function testUserControllerMethodsExist()
    {
        $userController = new UserController();

        $methods = [
            'checkCSRFToken',
            'register',
            'login',
            'getUserInfo',
            'logout'
        ];

        foreach ($methods as $method) {
            $this->assertTrue(
                method_exists($userController, $method), 
                "$method() function does not exist in UserController class"
            );
        }
    }

    public function testUserControllerMethodCount()
    {
        $userController = new UserController();

        // Get all methods of UserController, including inherited ones
        $reflector = new \ReflectionClass($userController);
        $methods = $reflector->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PRIVATE);

        // Filter out the methods that belong to the UserController class only
        $userControllerMethods = array_filter($methods, function ($method) use ($reflector) {
            return $method->getDeclaringClass()->getName() === $reflector->getName();
        });

        // There should be exactly 6 methods in the UserController class
        $this->assertCount(6, $userControllerMethods, 'UserController does not have exactly 6 methods');
    }
}
