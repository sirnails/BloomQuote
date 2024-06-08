<?php

namespace Tests;

require_once './config/database.php';
use PHPUnit\Framework\TestCase;
use App\Helpers\InputHelper;

class InputHelperTest extends TestCase
{
    public function testSanitizeInt()
    {
        $this->assertEquals(123, InputHelper::sanitizeInt('123'));
        $this->assertFalse(InputHelper::sanitizeInt('abc'));
    }
    public function testSanitizeString()
    {
        $this->assertEquals('Hello', InputHelper::sanitizeString('<b>Hello</b>'));

        // Test for basic HTML tags removal
        $this->assertEquals('Hello', InputHelper::sanitizeString('<b>Hello</b>'));

        // Test for possessive
        $this->assertEquals("the boy's ball", InputHelper::sanitizeString("the boy's ball"));

        // Test for possessive with HTML tags
        $this->assertEquals("the boy's ball", InputHelper::sanitizeString("<b>the boy's ball</b>"));

        // Test for multiple possessives with HTML tags
        $this->assertEquals("the boy's and girl's balls", InputHelper::sanitizeString("<b>the boy's</b> and <i>girl's</i> balls"));
    }
    public function testSanitizeArray()
    {
        $input = ['name' => '<b>John</b>', 'email' => 'john@example.com'];
        $expected = ['name' => 'John', 'email' => 'john@example.com'];
        $this->assertEquals($expected, InputHelper::sanitizeArray($input));
    }
    public function testSanitizeEmail()
    {
        $this->assertEquals('test@example.com', InputHelper::sanitizeEmail('test@example.com'));
        $this->assertFalse(InputHelper::sanitizeEmail('invalid-email'));
    }
}
?>