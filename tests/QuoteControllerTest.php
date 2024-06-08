<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\QuoteController;

require_once './config/database.php';

class QuoteControllerTest extends TestCase
{
    protected $quoteController;
    protected $lastInsertedId;

    protected function setUp(): void
    {
        $this->quoteController = new QuoteController();
        $_SESSION['user_id'] = 1; // Set a valid user ID for testing
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a valid CSRF token
    }

    protected function tearDown(): void
    {
        // Clean up: delete the test quote and its items from the database
        $this->deleteTestQuote();
    }

    public function testCreateQuote()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'csrf_token' => $_SESSION['csrf_token'],
            'wedding_date' => '2024-12-25',
            'billing_address' => '1234 Test St',
            'time' => '12:00',
            'bride_name' => 'Jane Doe',
            'bride_email' => 'jane.doe@example.com',
            'bride_contact' => '1234567890',
            'groom_name' => 'John Smith',
            'groom_email' => 'john.smith@example.com',
            'groom_contact' => '0987654321',
            'ceremony_address' => '5678 Example Rd',
            'venue_address' => '91011 Sample Ave',
            'other_address' => '',
            'days_for_deposit' => '30',
            'custom_message' => 'Congratulations!',
            'payment_terms' => 'Payment due one month before the wedding.'
        ];

        ob_start(); // Start output buffering
        $this->quoteController->create();
        ob_end_clean(); // Clean the output buffer

        $this->lastInsertedId = $this->quoteController->getLastInsertedQuoteId(); // Store the last inserted ID

        $this->assertEquals(302, http_response_code());
    }

    private function deleteTestQuote()
    {
        if ($this->lastInsertedId) {
            $db = db_connect();

            // Delete quote items associated with the quote
            $stmt = $db->prepare("DELETE FROM quote_items WHERE quote_id = ?");
            $stmt->bind_param("i", $this->lastInsertedId);
            $stmt->execute();
            $stmt->close();

            // Delete the quote
            $stmt = $db->prepare("DELETE FROM quotes WHERE id = ?");
            $stmt->bind_param("i", $this->lastInsertedId);
            $stmt->execute();
            $stmt->close();

            $db->close();
        }
    }

    public function testAddItemToQuote()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testEditItemInQuote()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testDeleteItemFromQuote()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testShowQuote()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testListQuotes()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testEditQuote()
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

    public function testPrintQuote()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testDeleteQuote()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testSearchQuotes()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testRecordPayment()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testViewReceipt()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

    public function testInitialPaymentInput()
    {
        // Placeholder test
        $this->markTestIncomplete();
    }

}
?>