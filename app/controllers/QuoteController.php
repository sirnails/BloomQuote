<?php

require_once './app/models/Quote.php';
require_once './app/models/QuoteItem.php';

class QuoteController {
    private $quoteModel;
    private $quoteItemModel;

    public function __construct() {
        $this->quoteModel = new Quote();
        $this->quoteItemModel = new QuoteItem();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'wedding_date' => $_POST['wedding_date'],
                'billing_address' => $_POST['billing_address'],
                'time' => $_POST['time'],
                'bride_name' => $_POST['bride_name'],
                'bride_email' => $_POST['bride_email'],
                'bride_contact' => $_POST['bride_contact'],
                'groom_name' => $_POST['groom_name'],
                'groom_email' => $_POST['groom_email'],
                'groom_contact' => $_POST['groom_contact'],
                'ceremony_address' => $_POST['ceremony_address'],
                'venue_address' => $_POST['venue_address'],
                'other_address' => $_POST['other_address'],
                'days_for_deposit' => $_POST['days_for_deposit'],
                'deposit_date' => date('Y-m-d', strtotime("+{$_POST['days_for_deposit']} days")),
                'final_consultation_month' => date('F Y', strtotime('-1 month', strtotime($_POST['wedding_date']))),
                'total_cost' => 0, // This will be calculated based on the line items
                'custom_message' => $_POST['custom_message'],
                'payment_terms' => $_POST['payment_terms']
            ];
            $this->quoteModel->create($data);
            header("Location: index.php?action=show_quote&id=" . $this->quoteModel->getLastInsertedId());
        } else {
            include_once './app/views/quote/create.php';
        }
    }

    public function add_item() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $quote_id = $_POST['quote_id'];
            $description = $_POST['description'];
            $delivery_location = $_POST['delivery_location'];
            $cost_per_item = $_POST['cost_per_item'];
            $quantity = $_POST['quantity'];
            $total_cost = $cost_per_item * $quantity;
    
            // Determine the next order value
            $next_order = $this->quoteItemModel->getNextOrderValue($quote_id);
    
            $this->quoteItemModel->create($quote_id, $description, $delivery_location, $cost_per_item, $quantity, $total_cost, $next_order);
    
            // Update the total cost of the quote
            $quote = $this->quoteModel->getQuoteById($quote_id);
            $new_total_cost = $quote['total_cost'] + $total_cost;
            $this->quoteModel->updateTotalCost($quote_id, $new_total_cost);
    
            header("Location: index.php?action=show_quote&id=" . $quote_id);
        } else {
            $quote_id = $_GET['quote_id'];
            include_once './app/views/quote/add_item.php';
        }
    }
    

    public function edit_item() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $item_id = $_POST['item_id'];
            $quote_id = $_POST['quote_id'];
            $description = $_POST['description'];
            $delivery_location = $_POST['delivery_location'];
            $cost_per_item = $_POST['cost_per_item'];
            $quantity = $_POST['quantity'];
            $total_cost = $cost_per_item * $quantity;

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
            $item_id = $_GET['item_id'];
            $item = $this->quoteItemModel->getItemById($item_id);
            include_once './app/views/quote/edit_item.php';
        }
    }

    public function delete_item() {
        $item_id = $_GET['item_id'];
        $quote_id = $_GET['quote_id'];
        $this->quoteItemModel->delete($item_id);
        $this->quoteItemModel->reorderItems($quote_id);
        header("Location: index.php?action=show_quote&id=" . $quote_id);
    }
    
    public function show($id) {
        $quote = $this->quoteModel->getQuoteById($id);
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
            $id = $_POST['id'];
            $data = [
                'wedding_date' => $_POST['wedding_date'],
                'billing_address' => $_POST['billing_address'],
                'time' => $_POST['time'],
                'bride_name' => $_POST['bride_name'],
                'bride_email' => $_POST['bride_email'],
                'bride_contact' => $_POST['bride_contact'],
                'groom_name' => $_POST['groom_name'],
                'groom_email' => $_POST['groom_email'],
                'groom_contact' => $_POST['groom_contact'],
                'ceremony_address' => $_POST['ceremony_address'],
                'venue_address' => $_POST['venue_address'],
                'other_address' => $_POST['other_address'],
                'days_for_deposit' => $_POST['days_for_deposit'],
                'deposit_date' => date('Y-m-d', strtotime("+{$_POST['days_for_deposit']} days")),
                'final_consultation_month' => date('F Y', strtotime('-1 month', strtotime($_POST['wedding_date']))),
                'custom_message' => $_POST['custom_message'],
                'payment_terms' => $_POST['payment_terms']
            ];
            $this->quoteModel->update($id, $data);
            header("Location: index.php?action=show_quote&id=" . $id);
        } else {
            $id = $_GET['id'];
            $quote = $this->quoteModel->getQuoteById($id);
            include_once './app/views/quote/edit_quote.php';
        }
    }

    public function move_item_up() {
        $item_id = $_GET['item_id'];
        $quote_id = $_GET['quote_id'];
        $this->quoteItemModel->moveItemUp($item_id);
        header("Location: index.php?action=show_quote&id=" . $quote_id);
    }
    
    public function move_item_down() {
        $item_id = $_GET['item_id'];
        $quote_id = $_GET['quote_id'];
        $this->quoteItemModel->moveItemDown($item_id);
        header("Location: index.php?action=show_quote&id=" . $quote_id);
    }
    
    public function print($id) {
        $quote = $this->quoteModel->getQuoteById($id);
        $items = $this->quoteItemModel->getItemsByQuoteId($id);
        include_once './app/views/quote/print.php';
    }
    
    public function deleteAllQuoteItems(){
        $quote_id = $_GET['id'];
        $this->quoteItemModel->deleteAllQuoteItems($quote_id);
    }

    public function deleteQuote(){
        $quote_id = $_GET['id'];
        $this->quoteModel->deleteQuote($quote_id);
        header("Location: index.php?action=view_quotes");

    }
}

?>
