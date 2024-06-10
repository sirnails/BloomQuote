<?php

namespace Tests;

require_once './config/database.php';

use PHPUnit\Framework\TestCase;
use App\Models\QuoteItem;

class QuoteItemTest extends TestCase
{
    protected $quoteItem;

    protected function setUp(): void
    {
        $this->quoteItem = new QuoteItem();
    }

    public function testCreateQuoteItem()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testGetNextOrderValue()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testGetItemsByQuoteId()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testGetItemById()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testUpdateQuoteItem()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testMoveItemUp()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testMoveItemDown()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testDeleteQuoteItem()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testReorderItems()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testCreatePayment()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testMarkAsPayment()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testGetPaymentById()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }
}
?>