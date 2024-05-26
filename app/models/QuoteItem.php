<?php
class QuoteItem {
    private $db;

    public function __construct() {
        $this->db = db_connect();
    }

    public function create($quote_id, $description, $delivery_location, $cost_per_item, $quantity, $total_cost, $order) {
        $stmt = $this->db->prepare("INSERT INTO quote_items (quote_id, description, delivery_location, cost_per_item, quantity, total_cost, `order`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdidi", $quote_id, $description, $delivery_location, $cost_per_item, $quantity, $total_cost, $order);
        return $stmt->execute();
    }

    public function getNextOrderValue($quote_id) {
        $stmt = $this->db->prepare("SELECT MAX(`order`) as max_order FROM quote_items WHERE quote_id = ?");
        $stmt->bind_param("i", $quote_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['max_order'] + 1;
    }

    public function getItemsByQuoteId($quote_id) {
        $stmt = $this->db->prepare("SELECT * FROM quote_items WHERE quote_id = ? ORDER BY `order` ASC");
        $stmt->bind_param("i", $quote_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getItemById($item_id) {
        $stmt = $this->db->prepare("SELECT * FROM quote_items WHERE id = ?");
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($item_id, $description, $delivery_location, $cost_per_item, $quantity, $total_cost) {
        $stmt = $this->db->prepare("UPDATE quote_items SET description = ?, delivery_location = ?, cost_per_item = ?, quantity = ?, total_cost = ? WHERE id = ?");
        $stmt->bind_param("ssdidd", $description, $delivery_location, $cost_per_item, $quantity, $total_cost, $item_id);
        return $stmt->execute();
    }

    public function moveItemUp($item_id) {
        $current_item = $this->getItemById($item_id);
        $current_order = $current_item['order'];
        $quote_id = $current_item['quote_id'];

        $stmt = $this->db->prepare("SELECT id, `order` FROM quote_items WHERE quote_id = ? AND `order` < ? ORDER BY `order` DESC LIMIT 1");
        $stmt->bind_param("ii", $quote_id, $current_order);
        $stmt->execute();
        $prev_item = $stmt->get_result()->fetch_assoc();

        if ($prev_item) {
            $prev_id = $prev_item['id'];
            $prev_order = $prev_item['order'];

            $stmt = $this->db->prepare("UPDATE quote_items SET `order` = ? WHERE id = ?");
            $stmt->bind_param("ii", $prev_order, $item_id);
            $stmt->execute();

            $stmt = $this->db->prepare("UPDATE quote_items SET `order` = ? WHERE id = ?");
            $stmt->bind_param("ii", $current_order, $prev_id);
            $stmt->execute();
        }
    }
    
    public function moveItemDown($item_id) {
        $current_item = $this->getItemById($item_id);
        $current_order = $current_item['order'];
        $quote_id = $current_item['quote_id'];
    
        $stmt = $this->db->prepare("SELECT id, `order` FROM quote_items WHERE quote_id = ? AND `order` > ? ORDER BY `order` ASC LIMIT 1");
        $stmt->bind_param("ii", $quote_id, $current_order);
        $stmt->execute();
        $next_item = $stmt->get_result()->fetch_assoc();
    
        if ($next_item) {
            $next_id = $next_item['id'];
            $next_order = $next_item['order'];
    
            $stmt = $this->db->prepare("UPDATE quote_items SET `order` = ? WHERE id = ?");
            $stmt->bind_param("ii", $next_order, $item_id);
            $stmt->execute();
    
            $stmt = $this->db->prepare("UPDATE quote_items SET `order` = ? WHERE id = ?");
            $stmt->bind_param("ii", $current_order, $next_id);
            $stmt->execute();
        }
    }
    
    public function delete($item_id) {
        $stmt = $this->db->prepare("DELETE FROM quote_items WHERE id = ?");
        $stmt->bind_param("i", $item_id);
        return $stmt->execute();
    }
    
    public function reorderItems($quote_id) {
        $stmt = $this->db->prepare("SELECT id FROM quote_items WHERE quote_id = ? ORDER BY `order` ASC");
        $stmt->bind_param("i", $quote_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = 1;
        while ($item = $result->fetch_assoc()) {
            $stmt = $this->db->prepare("UPDATE quote_items SET `order` = ? WHERE id = ?");
            $stmt->bind_param("ii", $order, $item['id']);
            $stmt->execute();
            $order++;
        }
    }
    
    public function deleteAllQuoteItems($quote_id) {
        $stmt = $this->db->prepare("DELETE FROM quote_items WHERE quote_id = ?");
        $stmt->bind_param("i", $quote_id);
        $stmt->execute();
        return $stmt->get_result();
    }

}
?>
