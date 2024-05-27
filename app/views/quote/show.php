<?php include_once './app/views/partials/navbar.php'; ?>
<a href="index.php?action=delete_quote&id=<?php echo $quote['id']; ?>" style="float: right;" onclick="return confirm('Are you sure you want to delete this quote forever?');" class="btn btn-danger mb-3">Delete</a>
<a href="index.php?action=edit_quote&id=<?php echo $quote['id']; ?>" class="btn btn-primary mb-3">Edit Quote Header</a>
<a href="index.php?action=print_quote&id=<?php echo $quote['id']; ?>" class="btn btn-success mb-3">Print Quote</a>
<h1>Quote Details</h1>
<strong>Wedding Date:</strong> <?php echo $quote['wedding_date']; ?></br>
<strong>Billing Address:</strong> <?php echo $quote['billing_address']; ?>
    <a href="https://www.google.com/maps/place/<?php echo urlencode($quote['billing_address']); ?>" target="_blank" class="btn btn-outline-primary btn-sm ml-2">G-Maps</a><a href="https://waze.com/ul?q=<?php echo urlencode($quote['billing_address']); ?>" target="_blank" class="btn btn-outline-info btn-sm ml-2">Waze</a>
</br>
<strong>Time:</strong> <?php echo $quote['time']; ?></br>
<strong>Name Bride/Person 1:</strong> <?php echo $quote['bride_name']; ?></br>
<strong>Email:</strong> <?php echo $quote['bride_email']; ?> <a href="mailto:<?php echo $quote['bride_email']; ?>" class="btn btn-outline-secondary btn-sm ml-2">Email</a></br>
<strong>Contact Nº 1:</strong> <?php echo $quote['bride_contact']; ?> <a href="tel:<?php echo $quote['bride_contact']; ?>" class="btn btn-outline-secondary btn-sm ml-2">Call</a></br>
<strong>Name Groom/Person 2:</strong> <?php echo $quote['groom_name']; ?></br>
<strong>Email:</strong> <?php echo $quote['groom_email']; ?><a href="mailto:<?php echo $quote['groom_email']; ?>" class="btn btn-outline-secondary btn-sm ml-2">Email</a></br>
<strong>Contact Nº 2:</strong> <?php echo $quote['groom_contact']; ?><a href="tel:<?php echo $quote['groom_contact']; ?>" class="btn btn-outline-secondary btn-sm ml-2">Call</a></br>

<strong>Ceremony/Church Address:</strong> <?php echo $quote['ceremony_address']; ?>
    <a href="https://www.google.com/maps/place/<?php echo urlencode($quote['ceremony_address']); ?>" target="_blank" class="btn btn-outline-primary btn-sm ml-2">G-Maps</a>    <a href="https://waze.com/ul?q=<?php echo urlencode($quote['ceremony_address']); ?>" target="_blank" class="btn btn-outline-info btn-sm ml-2">Waze</a></br>

<strong>Breakfast/Venue Address:</strong> <?php echo $quote['venue_address']; ?>
    <a href="https://www.google.com/maps/place/<?php echo urlencode($quote['venue_address']); ?>" target="_blank" class="btn btn-outline-primary btn-sm ml-2">G-Maps</a>    <a href="https://waze.com/ul?q=<?php echo urlencode($quote['venue_address']); ?>" target="_blank" class="btn btn-outline-info btn-sm ml-2">Waze</a></br>

<strong>Other Address:</strong> <?php echo $quote['other_address']; ?>
    <a href="https://www.google.com/maps/place/<?php echo urlencode($quote['other_address']); ?>" target="_blank" class="btn btn-outline-primary btn-sm ml-2">G-Maps</a>    <a href="https://waze.com/ul?q=<?php echo urlencode($quote['other_address']); ?>" target="_blank" class="btn btn-outline-info btn-sm ml-2">Waze</a></br>

<strong>Days for Deposit:</strong> <?php echo $quote['days_for_deposit']; ?></br>
<strong>Deposit Date:</strong> <?php echo $quote['deposit_date']; ?></br>
<strong>Month of Final Consultation:</strong> <?php echo $quote['final_consultation_month']; ?></br>
<strong>Total Cost:</strong> £<?php echo number_format($quote['total_cost'], 2); ?></br>
<p><strong>Custom Message:</strong> <?php echo $quote['custom_message']; ?></p>


<h2>Quote Items</h2>
<table class="table">
    <thead>
        <tr>
            <th>Details</th>
            <th>Deliver to</th>
            <th>Cost per Item</th>
            <th>Number of Items</th>
            <th>Total Cost</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($item = $items->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $item['description']; ?></td>
                <td><?php echo $item['delivery_location']; ?></td>
                <td>£<?php echo number_format($item['cost_per_item'], 2); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>£<?php echo number_format($item['total_cost'], 2); ?></td>
                <td>
                    <div class="button-grid">
                        <a href="index.php?action=move_item_up&item_id=<?php echo $item['id']; ?>&quote_id=<?php echo $quote['id']; ?>" class="btn btn-secondary">&#x2191;</a>
                        <a href="index.php?action=edit_item&item_id=<?php echo $item['id']; ?>" class="btn btn-info">Edit</a>
                        <a href="index.php?action=move_item_down&item_id=<?php echo $item['id']; ?>&quote_id=<?php echo $quote['id']; ?>" class="btn btn-secondary">&#x2193;</a>
                        <a href="index.php?action=delete_item&item_id=<?php echo $item['id']; ?>&quote_id=<?php echo $quote['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">&#x1F5D1;</a>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<h2>Add Quote Item</h2>
<form method="POST" action="index.php?action=add_item">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="hidden" name="quote_id" value="<?php echo $quote['id']; ?>">
    <div class="form-group">
        <label for="description">Details</label>
        <textarea class="form-control" id="description" name="description" required rows="5"></textarea>
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

<p><strong>Payment Terms:</strong> <?php echo $quote['payment_terms']; ?></p>

<?php include_once './app/views/partials/footer.php'; ?>
