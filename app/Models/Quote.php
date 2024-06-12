<?php
namespace App\Models;

require_once './config/database.php';

class Quote {
    private $db;
    public function __construct() {
        $this->db = \db_connect();
    }
    public function getNextWeddingGroup($user_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM quotes 
            WHERE user_id = ? AND wedding_date >= CURDATE() 
            ORDER BY wedding_date ASC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $weddings = [];
        while ($row = $result->fetch_assoc()) {
            $wedding_date = $row['wedding_date'];
            if (!isset($weddings[$wedding_date])) {
                $weddings[$wedding_date] = [];
            }
            $weddings[$wedding_date][] = $row;
        }
        return $weddings;
    }
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO quotes (user_id, wedding_date, billing_address, time, bride_name, bride_email, bride_contact, groom_name, groom_email, groom_contact, ceremony_address, venue_address, other_address, days_for_deposit, deposit_date, final_consultation_month, total_cost, custom_message, payment_terms) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssssssissdss", 
        $data['user_id'], 
        $data['wedding_date'], 
        $data['billing_address'], 
        $data['time'], 
        $data['bride_name'], 
        $data['bride_email'], 
        $data['bride_contact'], 
        $data['groom_name'], 
        $data['groom_email'], 
        $data['groom_contact'], 
        $data['ceremony_address'], 
        $data['venue_address'], 
        $data['other_address'], 
        $data['days_for_deposit'], 
        $data['deposit_date'], 
        $data['final_consultation_month'], 
        $data['total_cost'], 
        $data['custom_message'], 
        $data['payment_terms']);
        $stmt->execute();
    }
    public function getQuoteById($id) {
        $stmt = $this->db->prepare("SELECT * FROM quotes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function getLastInsertedId() {
        return $this->db->insert_id;
    }
    public function updateTotalCost($quote_id, $total_cost) {
        $stmt = $this->db->prepare("UPDATE quotes SET total_cost = ? WHERE id = ?");
        $stmt->bind_param("di", $total_cost, $quote_id);
        $stmt->execute();
    }
    public function getQuotesByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM quotes WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE quotes SET wedding_date = ?, billing_address = ?, time = ?, bride_name = ?, bride_email = ?, bride_contact = ?, groom_name = ?, groom_email = ?, groom_contact = ?, ceremony_address = ?, venue_address = ?, other_address = ?, days_for_deposit = ?, deposit_date = ?, final_consultation_month = ?, custom_message = ?, payment_terms = ? WHERE id = ?");
        $stmt->bind_param("sssssssssssssssssi", 
        $data['wedding_date'], 
        $data['billing_address'], 
        $data['time'], 
        $data['bride_name'], 
        $data['bride_email'], 
        $data['bride_contact'], 
        $data['groom_name'], 
        $data['groom_email'], 
        $data['groom_contact'], 
        $data['ceremony_address'], 
        $data['venue_address'], 
        $data['other_address'], 
        $data['days_for_deposit'], 
        $data['deposit_date'], 
        $data['final_consultation_month'], 
        $data['custom_message'], 
        $data['payment_terms'], $id);
        return $stmt->execute();
    }
    public function deleteQuote($quote_id) {
        $stmt = $this->db->prepare("DELETE FROM quotes WHERE id = ?");
        $stmt->bind_param("i", $quote_id);
        return $stmt->execute();
    }
    public function searchQuotesByUserId($user_id, $search_term) {
        $like_search_term = '%' . $search_term . '%';
        $query = "
            SELECT * FROM quotes 
            WHERE user_id = ? AND (
                bride_name LIKE ? OR 
                groom_name LIKE ? OR 
                wedding_date LIKE ? OR 
                billing_address LIKE ? OR 
                time LIKE ? OR 
                bride_email LIKE ? OR 
                bride_contact LIKE ? OR 
                groom_email LIKE ? OR 
                groom_contact LIKE ? OR 
                ceremony_address LIKE ? OR 
                venue_address LIKE ? OR 
                other_address LIKE ? OR 
                custom_message LIKE ? OR 
                payment_terms LIKE ?
            )
        ";
        
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("issssssssssssss", 
                $user_id, $like_search_term, $like_search_term, $like_search_term, 
                $like_search_term, $like_search_term, $like_search_term, $like_search_term, 
                $like_search_term, $like_search_term, $like_search_term, $like_search_term, 
                $like_search_term, $like_search_term, $like_search_term
            );
            
            if ($stmt->execute()) {
                return $stmt->get_result();
            } else {
                error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                throw new Exception("Database execute failed");
            }
        } else {
            error_log("Prepare failed: (" . $this->db->errno . ") " . $this->db->error);
            throw new Exception("Database prepare failed");
        }
    }
    public function recalculateTotalCost($quote_id) {
        $stmt = $this->db->prepare("SELECT SUM(total_cost) as total FROM quote_items WHERE quote_id = ?");
        $stmt->bind_param("i", $quote_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $total_cost = $result['total'];
    
        $stmt = $this->db->prepare("UPDATE quotes SET total_cost = ? WHERE id = ?");
        $stmt->bind_param("di", $total_cost, $quote_id);
        $stmt->execute();
    
        return $total_cost;
    }
    public function deletePaymentsByQuoteId($quote_id) {
        // Logging start of the method
        //error_log("Starting deletePaymentsByQuoteId for quote_id: $quote_id");
    
        // Get all quote item IDs associated with the quote ID
        $stmt = $this->db->prepare("SELECT id, description, delivery_location, cost_per_item, quantity, total_cost, is_payment FROM quote_items WHERE quote_id = ?");
        if (!$stmt) {
            error_log("Failed to prepare statement: " . $this->db->error);
            return;
        }
        $stmt->bind_param("i", $quote_id);
        if (!$stmt->execute()) {
            error_log("Failed to execute statement: " . $stmt->error);
            return;
        }
        $result = $stmt->get_result();
        if (!$result) {
            error_log("Failed to get result: " . $stmt->error);
            return;
        }
    
        // Log if no rows are retrieved
        if ($result->num_rows === 0) {
            //error_log("No quote items found for quote_id: $quote_id");
            return;
        }
    
        // Log the retrieved quote item details
        $quoteItemIds = [];
        while ($row = $result->fetch_assoc()) {
            $quoteItemIds[] = $row['id'];
            //error_log("Quote item - ID: {$row['id']}, Description: {$row['description']}, Delivery Location: {$row['delivery_location']}, Cost Per Item: {$row['cost_per_item']}, Quantity: {$row['quantity']}, Total Cost: {$row['total_cost']}, Is Payment: {$row['is_payment']}");
            
            // If is_payment is not zero, delete the corresponding payment
            if ($row['is_payment'] != 0) {
                $payment_id = $row['is_payment'];
                //error_log("Deleting payment for payment_id: $payment_id");
                $deletePaymentStmt = $this->db->prepare("DELETE FROM payments WHERE id = ?");
                if (!$deletePaymentStmt) {
                    error_log("Failed to prepare delete payment statement: " . $this->db->error);
                    return;
                }
                $deletePaymentStmt->bind_param("i", $payment_id);
                if (!$deletePaymentStmt->execute()) {
                    error_log("Failed to execute delete payment statement: " . $deletePaymentStmt->error);
                }
            }
        }
        //error_log("Retrieved quote item IDs for quote_id $quote_id: " . implode(", ", $quoteItemIds));
        // Now delete the quote items themselves
        $deleteItemsStmt = $this->db->prepare("DELETE FROM quote_items WHERE quote_id = ?");
        if (!$deleteItemsStmt) {
            //error_log("Failed to prepare delete quote items statement: " . $this->db->error);
            return;
        }
        $deleteItemsStmt->bind_param("i", $quote_id);
        if (!$deleteItemsStmt->execute()) {
            error_log("Failed to execute delete quote items statement: " . $deleteItemsStmt->error);
        }
        //error_log("Completed deletePaymentsByQuoteId for quote_id: $quote_id");
    }
}
?>
