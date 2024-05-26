<?php
class Quote {
    private $db;

    public function __construct() {
        $this->db = db_connect();
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
    
}
?>
