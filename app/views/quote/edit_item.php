<?php include_once './app/views/partials/navbar.php'; ?>
        <h1>Edit Quote Item</h1>
        <form method="POST" action="index.php?action=edit_item">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
            <input type="hidden" name="quote_id" value="<?php echo $item['quote_id']; ?>">
            <div class="form-group">
                <label for="description">Details</label>
                <textarea class="form-control" id="description" name="description" required rows="10"><?php echo $item['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="delivery_location">Deliver to</label>
                <select class="form-control" id="delivery_location" name="delivery_location" required>
                    <option value="Billing Address" <?php echo $item['delivery_location'] == 'Billing Address' ? 'selected' : ''; ?>>Billing Address</option>
                    <option value="Ceremony/Church Address" <?php echo $item['delivery_location'] == 'Ceremony/Church Address' ? 'selected' : ''; ?>>Ceremony/Church Address</option>
                    <option value="Breakfast/Venue Address" <?php echo $item['delivery_location'] == 'Breakfast/Venue Address' ? 'selected' : ''; ?>>Breakfast/Venue Address</option>
                    <option value="Other Address" <?php echo $item['delivery_location'] == 'Other Address' ? 'selected' : ''; ?>>Other Address</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cost_per_item">Cost Per Item</label>
                <input type="number" class="form-control" id="cost_per_item" name="cost_per_item" step="0.01" value="<?php echo $item['cost_per_item']; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Number of Items</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $item['quantity']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

<?php include_once './app/views/partials/footer.php'; ?>
