<?php

require_once './app/models/Quote.php';
require_once './app/models/QuoteItem.php';
require_once './app/helpers/SanitizationHelper.php';
require_once './app/helpers/InputHelper.php';

class QuoteController {
    private $quoteModel;
    private $quoteItemModel;

    public function __construct() {
        $this->quoteModel = new Quote();
        $this->quoteItemModel = new QuoteItem();
    }

    private function checkCSRFToken($token) {
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            die('Invalid CSRF token');
        }
    }

    private function authorizeUser($quote_id) {
        $quote = $this->quoteModel->getQuoteById($quote_id);
        if ($quote['user_id'] !== $_SESSION['user_id']) {
            header("Location: index.php?action=view_quotes");
            die('Unauthorized access');
        }
        return $quote; // return quote for further use
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->checkCSRFToken($_POST['csrf_token']);
            
            $sanitizedData = SanitizationHelper::sanitizeArray($_POST);
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'wedding_date' => $sanitizedData['wedding_date'],
                'billing_address' => $sanitizedData['billing_address'],
                'time' => $sanitizedData['time'],
                'bride_name' => $sanitizedData['bride_name'],
                'bride_email' => $sanitizedData['bride_email'],
                'bride_contact' => $sanitizedData['bride_contact'],
                'groom_name' => $sanitizedData['groom_name'],
                'groom_email' => $sanitizedData['groom_email'],
                'groom_contact' => $sanitizedData['groom_contact'],
                'ceremony_address' => $sanitizedData['ceremony_address'],
                'venue_address' => $sanitizedData['venue_address'],
                'other_address' => $sanitizedData['other_address'],
                'days_for_deposit' => $sanitizedData['days_for_deposit'],
                'deposit_date' => date('Y-m-d', strtotime("+{$sanitizedData['days_for_deposit']} days")),
                'final_consultation_month' => date('F Y', strtotime('-1 month', strtotime($sanitizedData['wedding_date']))),
                'total_cost' => 0, // This will be calculated based on the line items
                'custom_message' => $sanitizedData['custom_message'],
                'payment_terms' => $sanitizedData['payment_terms']
            ];
            $this->quoteModel->create($data);
            header("Location: index.php?action=show_quote&id=" . $this->quoteModel->getLastInsertedId());
        } else {
            include_once './app/views/quote/create.php';
        }
    }

    public function add_item() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->checkCSRFToken($_POST['csrf_token']);
            
            $sanitizedData = SanitizationHelper::sanitizeArray($_POST);

            $quote_id = InputHelper::sanitizeInt($sanitizedData['quote_id']);
            $quote = $this->authorizeUser($quote_id); // Check authorization

            $description = SanitizationHelper::sanitizeInput($sanitizedData['description']);
            $delivery_location = SanitizationHelper::sanitizeInput($sanitizedData['delivery_location']);
            $cost_per_item = floatval($sanitizedData['cost_per_item']);
            $quantity = intval($sanitizedData['quantity']);
            $total_cost = $cost_per_item * $quantity;

            if ($quote_id === false) {
                die('Invalid quote ID');
            }
    
            // Determine the next order value
            $next_order = $this->quoteItemModel->getNextOrderValue($quote_id);
    
            $this->quoteItemModel->create($quote_id, $description, $delivery_location, $cost_per_item, $quantity, $total_cost, $next_order);
    
            // Update the total cost of the quote
            $quote = $this->quoteModel->getQuoteById($quote_id);
            $new_total_cost = $quote['total_cost'] + $total_cost;
            $this->quoteModel->updateTotalCost($quote_id, $new_total_cost);
    
            header("Location: index.php?action=show_quote&id=" . $quote_id);
        } else {
            $quote_id = InputHelper::sanitizeInt($_GET['quote_id']);
            if ($quote_id === false) {
                die('Invalid quote ID');
            }
            $quote = $this->authorizeUser($quote_id); // Check authorization
            include_once './app/views/quote/add_item.php';
        }
    }

    public function edit_item() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->checkCSRFToken($_POST['csrf_token']);
            
            $sanitizedData = SanitizationHelper::sanitizeArray($_POST);

            $item_id = InputHelper::sanitizeInt($sanitizedData['item_id']);
            $quote_id = InputHelper::sanitizeInt($sanitizedData['quote_id']);
            $quote = $this->authorizeUser($quote_id); // Check authorization

            $description = SanitizationHelper::sanitizeInput($sanitizedData['description']);
            $delivery_location = SanitizationHelper::sanitizeInput($sanitizedData['delivery_location']);
            $cost_per_item = floatval($sanitizedData['cost_per_item']);
            $quantity = intval($sanitizedData['quantity']);
            $total_cost = $cost_per_item * $quantity;

            if ($item_id === false || $quote_id === false) {
                die('Invalid item or quote ID');
            }

            // Update the quote item
            $this->quoteItemModel->update($item_id, $description, $delivery_location, $cost_per_item, $quantity, $total_cost);

            // Recalculate the total cost of the quote
            $items = $this->quoteItemModel->getItemsByQuoteId($quote_id);
            $new_total_cost = 0;
            while ($item = $items->fetch_assoc()) {
                $new_total_cost += $item['total_cost'];
            }
            $this->quoteModel->updateTotalCost($quote_id, $new_total_cost);

            header("Location: index.php?action=show_quote&id=" . $quote_id);
        } else {
            $item_id = InputHelper::sanitizeInt($_GET['item_id']);
            if ($item_id === false) {
                die('Invalid item ID');
            }
            $item = $this->quoteItemModel->getItemById($item_id);
            $quote = $this->authorizeUser($item['quote_id']); // Check authorization
            include_once './app/views/quote/edit_item.php';
        }
    }

    public function delete_item() {
        $item_id = InputHelper::sanitizeInt($_GET['item_id']);
        $quote_id = InputHelper::sanitizeInt($_GET['quote_id']);
        $quote = $this->authorizeUser($quote_id); // Check authorization

        if ($item_id === false || $quote_id === false) {
            die('Invalid item or quote ID');
        }

        $this->quoteItemModel->delete($item_id);
        $this->quoteItemModel->reorderItems($quote_id);
        header("Location: index.php?action=show_quote&id=" . $quote_id);
    }
    
    public function show($id) {
        $id = InputHelper::sanitizeInt($id);
        if ($id === false) {
            die('Invalid quote ID');
        }
        $quote = $this->authorizeUser($id); // Check authorization
        $items = $this->quoteItemModel->getItemsByQuoteId($id);
        include_once './app/views/quote/show.php';
    }

    public function list_quotes() {
        $user_id = $_SESSION['user_id'];
        $quotes = $this->quoteModel->getQuotesByUserId($user_id);
        include_once './app/views/quote/list_quotes.php';
    }

    public function edit_quote() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->checkCSRFToken($_POST['csrf_token']);
            
            $sanitizedData = SanitizationHelper::sanitizeArray($_POST);

            $id = InputHelper::sanitizeInt($sanitizedData['id']);
            if ($id === false) {
                die('Invalid quote ID');
            }
            $quote = $this->authorizeUser($id); // Check authorization

            $data = [
                'wedding_date' => $sanitizedData['wedding_date'],
                'billing_address' => $sanitizedData['billing_address'],
                'time' => $sanitizedData['time'],
                'bride_name' => $sanitizedData['bride_name'],
                'bride_email' => $sanitizedData['bride_email'],
                'bride_contact' => $sanitizedData['bride_contact'],
                'groom_name' => $sanitizedData['groom_name'],
                'groom_email' => $sanitizedData['groom_email'],
                'groom_contact' => $sanitizedData['groom_contact'],
                'ceremony_address' => $sanitizedData['ceremony_address'],
                'venue_address' => $sanitizedData['venue_address'],
                'other_address' => $sanitizedData['other_address'],
                'days_for_deposit' => $sanitizedData['days_for_deposit'],
                'deposit_date' => date('Y-m-d', strtotime("+{$sanitizedData['days_for_deposit']} days")),
                'final_consultation_month' => date('F Y', strtotime('-1 month', strtotime($sanitizedData['wedding_date']))),
                'custom_message' => $sanitizedData['custom_message'],
                'payment_terms' => $sanitizedData['payment_terms']
            ];
            $this->quoteModel->update($id, $data);
            header("Location: index.php?action=show_quote&id=" . $id);
        } else {
            $id = InputHelper::sanitizeInt($_GET['id']);
            if ($id === false) {
                die('Invalid quote ID');
            }
            $quote = $this->authorizeUser($id); // Check authorization
            include_once './app/views/quote/edit_quote.php';
        }
    }

    public function move_item_up() {
        $item_id = InputHelper::sanitizeInt($_GET['item_id']);
        $quote_id = InputHelper::sanitizeInt($_GET['quote_id']);
        $quote = $this->authorizeUser($quote_id); // Check authorization

        if ($item_id === false || $quote_id === false) {
            die('Invalid item or quote ID');
        }
        $this->quoteItemModel->moveItemUp($item_id);
        header("Location: index.php?action=show_quote&id=" . $quote_id);
    }
    
    public function move_item_down() {
        $item_id = InputHelper::sanitizeInt($_GET['item_id']);
        $quote_id = InputHelper::sanitizeInt($_GET['quote_id']);
        $quote = $this->authorizeUser($quote_id); // Check authorization

        if ($item_id === false || $quote_id === false) {
            die('Invalid item or quote ID');
        }
        $this->quoteItemModel->moveItemDown($item_id);
        header("Location: index.php?action=show_quote&id=" . $quote_id);
    }
    
    public function print($id) {
        $quote = $this->authorizeUser($id); // Check authorization
        $items = $this->quoteItemModel->getItemsByQuoteId($id);
        include_once './app/views/quote/print.php';
    }
    
    public function deleteAllQuoteItems(){
        $quote_id = InputHelper::sanitizeInt($_GET['id']);
        if ($quote_id === false) {
            die('Invalid quote ID');
        }
        $quote = $this->authorizeUser($quote_id); // Check authorization
        
        $this->quoteItemModel->deleteAllQuoteItems($quote_id);
    }

    public function deleteQuote(){
        $quote_id = InputHelper::sanitizeInt($_GET['id']);
        if ($quote_id === false) {
            die('Invalid quote ID');
        }
        $quote = $this->authorizeUser($quote_id); // Check authorization

        $this->quoteItemModel->deleteAllQuoteItems($quote_id);
        $this->quoteModel->deleteQuote($quote_id);
        header("Location: index.php?action=view_quotes");
    }

    public function search_quotes() {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search_term'])) {
                $search_term = InputHelper::sanitizeString($_GET['search_term']);
                $user_id = $_SESSION['user_id'];
                $quotes = $this->quoteModel->searchQuotesByUserId($user_id, $search_term);
                include_once './app/views/quote/list_quotes.php';
            } else {
                header("Location: index.php?action=view_quotes");
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: index.php?action=view_quotes");
        }
    }
    public function record_payment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->checkCSRFToken($_POST['csrf_token']);
    
            $sanitizedData = SanitizationHelper::sanitizeArray($_POST);
            $stage = isset($sanitizedData['stage']) ? $sanitizedData['stage'] : 'initial'; // Default to 'initial' if stage is not set
    
            if ($stage == 'initial') {
                // Handle initial payment input
                $quote_id = InputHelper::sanitizeInt($sanitizedData['quote_id']);
                $quote = $this->authorizeUser($quote_id); // Check authorization
    
                $amount_paid = floatval($sanitizedData['amount_paid']);
                $total_cost = floatval($quote['total_cost']);
    
                // Create new quote item for the payment
                $description = 'Payment';
                $delivery_location = 'Billing Address';
                $cost_per_item = -abs($amount_paid); // Ensure negative amount
                $quantity = 1;
                $total_cost = $cost_per_item * $quantity;
    
                // Determine the next order value
                $next_order = $this->quoteItemModel->getNextOrderValue($quote_id);
    
                $quote_item_id = $this->quoteItemModel->create($quote_id, $description, $delivery_location, $cost_per_item, $quantity, $total_cost, $next_order);
    
                $message = "Created quote_item_id: $quote_item_id";
                $logFile = 'payment_log.txt';
                file_put_contents($logFile, $message . PHP_EOL, FILE_APPEND);


                // Calculate outstanding balance
                $outstanding_balance = $quote['total_cost'] + $total_cost;
    
                // Get the final consultation month
                $final_consultation_month = $quote['final_consultation_month'];
                $due_date = date('Y-m-01', strtotime($final_consultation_month));
                $consultation_date = $due_date;
                $thank_you_message = 'Thanks for your payment';
    
                // Insert payment record
                $payment_id = $this->quoteItemModel->createPayment($quote_item_id, $amount_paid, $outstanding_balance, $due_date, $consultation_date, $thank_you_message);
    
                // Update quote item to mark as payment
                $this->quoteItemModel->markAsPayment($quote_item_id, $payment_id);
    
                // Update the total cost of the quote
                $this->quoteModel->updateTotalCost($quote_id, $outstanding_balance);
    
                header("Location: index.php?action=record_payment&quote_id=" . $quote_id . "&quote_item_id=" . $quote_item_id . "&amount_paid=" . $amount_paid . "&outstanding_balance=" . $outstanding_balance . "&due_date=" . $due_date . "&consultation_date=" . $consultation_date . "&thank_you_message=" . urlencode($thank_you_message));
            } else if ($stage == 'final') {
                // Handle final payment recording
                $quote_id = InputHelper::sanitizeInt($sanitizedData['quote_id']);
                $quote_item_id = InputHelper::sanitizeInt($sanitizedData['quote_item_id']);
                $amount_paid = floatval($sanitizedData['amount_paid']);
                $outstanding_balance = floatval($sanitizedData['outstanding_balance']);
                $due_date = $sanitizedData['due_date'];
                $consultation_date = $sanitizedData['consultation_date'];
                $thank_you_message = SanitizationHelper::sanitizeInput($sanitizedData['thank_you_message']);
    
                // Redirect to the quote page after recording the payment
                header("Location: index.php?action=show_quote&id=" . $quote_id);
            }
        } else {
            $quote_id = InputHelper::sanitizeInt($_GET['quote_id']);
            if ($quote_id === false) {
                die('Invalid quote ID');
            }
            $quote = $this->authorizeUser($quote_id); // Check authorization
            $quote_item_id = InputHelper::sanitizeInt($_GET['quote_item_id']);
            $amount_paid = floatval($_GET['amount_paid']);
            $outstanding_balance = floatval($_GET['outstanding_balance']);
            $due_date = $_GET['due_date'];
            $consultation_date = $_GET['consultation_date'];
            $thank_you_message = $_GET['thank_you_message'];
    
            include_once './app/views/quote/record_payment.php';
        }
    }
    
    
    public function view_receipt($payment_id) {
        $payment_id = InputHelper::sanitizeInt($payment_id);
        if ($payment_id === false) {
            die('Invalid payment ID');
        }
        $payment = $this->quoteItemModel->getPaymentById($payment_id);
        include_once './app/views/quote/view_receipt.php';
    }

    public function initial_payment_input() {
        $quote_id = InputHelper::sanitizeInt($_GET['quote_id']);
        if ($quote_id === false) {
            die('Invalid quote ID');
        }
        $quote = $this->authorizeUser($quote_id); // Check authorization
        include_once './app/views/quote/initial_payment_input.php';
    }
    
    
}

?>