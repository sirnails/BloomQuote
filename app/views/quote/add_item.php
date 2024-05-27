<?php include_once './app/views/partials/navbar.php'; ?>

        <h1>Add Quote Item</h1>
        <form method="POST" action="index.php?action=add_item">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="quote_id" value="<?php echo $_GET['quote_id']; ?>">
            <div class="form-group">
                <label for="description">Details</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="delivery_location">Deliver to</label>
                <select class="form-control" id="delivery_location" name="delivery_location" required>
                    <option value="Billing Address">Billing Address</option>
                    <option value="Ceremony/Church Address">Ceremony/Church Address</option>
                    <option value="Breakfast/Venue Address">Breakfast/Venue Address</option>
                    <option value="Other Address">Other Address</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cost_per_item">Cost Per Item</label>
                <input type="number" class="form-control" id="cost_per_item" name="cost_per_item" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="quantity">Number of Items</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Item</button>
        </form>

<?php include_once './app/views/partials/footer.php'; ?>
