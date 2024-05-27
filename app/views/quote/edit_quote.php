<?php include_once './app/views/partials/navbar.php'; ?>
        <h1>Edit Quote Header</h1>
        <form method="POST" action="index.php?action=edit_quote">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="id" value="<?php echo $quote['id']; ?>">
            <div class="form-group">
                <label for="wedding_date">Wedding Date</label>
                <input type="date" class="form-control" id="wedding_date" name="wedding_date" value="<?php echo $quote['wedding_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="billing_address">Billing Address</label>
                <input type="text" class="form-control" id="billing_address" name="billing_address" value="<?php echo $quote['billing_address']; ?>" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" class="form-control" id="time" name="time" value="<?php echo $quote['time']; ?>" required>
            </div>
            <div class="form-group">
                <label for="bride_name">Name Bride/Person 1</label>
                <input type="text" class="form-control" id="bride_name" name="bride_name" value="<?php echo $quote['bride_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="bride_email">Email</label>
                <input type="email" class="form-control" id="bride_email" name="bride_email" value="<?php echo $quote['bride_email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="bride_contact">Contact Nº 1</label>
                <input type="text" class="form-control" id="bride_contact" name="bride_contact" value="<?php echo $quote['bride_contact']; ?>" required>
            </div>
            <div class="form-group">
                <label for="groom_name">Name Groom/Person 2</label>
                <input type="text" class="form-control" id="groom_name" name="groom_name" value="<?php echo $quote['groom_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="groom_email">Email</label>
                <input type="email" class="form-control" id="groom_email" name="groom_email" value="<?php echo $quote['groom_email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="groom_contact">Contact Nº 2</label>
                <input type="text" class="form-control" id="groom_contact" name="groom_contact" value="<?php echo $quote['groom_contact']; ?>" required>
            </div>
            <div class="form-group">
                <label for="ceremony_address">Ceremony/Church Address</label>
                <input type="text" class="form-control" id="ceremony_address" name="ceremony_address" value="<?php echo $quote['ceremony_address']; ?>" required>
            </div>
            <div class="form-group">
                <label for="venue_address">Breakfast/Venue Address</label>
                <input type="text" class="form-control" id="venue_address" name="venue_address" value="<?php echo $quote['venue_address']; ?>" required>
            </div>
            <div class="form-group">
                <label for="other_address">Other Address</label>
                <input type="text" class="form-control" id="other_address" name="other_address" value="<?php echo $quote['other_address']; ?>">
            </div>
            <div class="form-group">
                <label for="days_for_deposit">Days for Deposit</label>
                <input type="number" class="form-control" id="days_for_deposit" name="days_for_deposit" value="<?php echo $quote['days_for_deposit']; ?>" required>
            </div>
            <div class="form-group">
                <label for="custom_message">Custom Message</label>
                <textarea class="form-control" id="custom_message" name="custom_message"><?php echo $quote['custom_message']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="payment_terms">Payment Terms</label>
                <textarea class="form-control" id="payment_terms" name="payment_terms"><?php echo $quote['payment_terms']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

<?php include_once './app/views/partials/footer.php'; ?>
