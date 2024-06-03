<?php
class Quote {
    private $db;

    public function __construct() {
        $this->db = db_connect();
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
    
}
?>
