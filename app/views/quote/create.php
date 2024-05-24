<?php include_once './app/views/partials/navbar.php'; ?>
        <h1>Create Quote</h1>
        <form method="POST" action="index.php?action=create_quote">
            <div class="form-group">
                <label for="wedding_date">Wedding Date</label>
                <input type="date" class="form-control" id="wedding_date" name="wedding_date" required>
            </div>
            <div class="form-group">
                <label for="billing_address">Billing Address</label>
                <input type="text" class="form-control" id="billing_address" name="billing_address" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
            <div class="form-group">
                <label for="bride_name">Name Bride/Person 1</label>
                <input type="text" class="form-control" id="bride_name" name="bride_name" required>
            </div>
            <div class="form-group">
                <label for="bride_email">Email</label>
                <input type="email" class="form-control" id="bride_email" name="bride_email" required>
            </div>
            <div class="form-group">
                <label for="bride_contact">Contact Nº 1</label>
                <input type="text" class="form-control" id="bride_contact" name="bride_contact" required>
            </div>
            <div class="form-group">
                <label for="groom_name">Name Groom/Person 2</label>
                <input type="text" class="form-control" id="groom_name" name="groom_name" required>
            </div>
            <div class="form-group">
                <label for="groom_email">Email</label>
                <input type="email" class="form-control" id="groom_email" name="groom_email" required>
            </div>
            <div class="form-group">
                <label for="groom_contact">Contact Nº 2</label>
                <input type="text" class="form-control" id="groom_contact" name="groom_contact" required>
            </div>
            <div class="form-group">
                <label for="ceremony_address">Ceremony/Church Address</label>
                <input type="text" class="form-control" id="ceremony_address" name="ceremony_address" required>
            </div>
            <div class="form-group">
                <label for="venue_address">Breakfast/Venue Address</label>
                <input type="text" class="form-control" id="venue_address" name="venue_address" required>
            </div>
            <div class="form-group">
                <label for="other_address">Other Address</label>
                <input type="text" class="form-control" id="other_address" name="other_address">
            </div>
            <div class="form-group">
                <label for="days_for_deposit">Days for Deposit</label>
                <input type="number" class="form-control" id="days_for_deposit" name="days_for_deposit" required>
            </div>
            <div class="form-group">
                <label for="custom_message">Custom Message</label>
                <textarea class="form-control" id="custom_message" name="custom_message"  rows="10">For your wedding</textarea>
            </div>
            <div class="form-group">
                <label for="payment_terms">Payment Terms</label>
                <textarea class="form-control" id="payment_terms" name="payment_terms" rows="30">All of these creations are crafted with the finest British flowers in season, ensuring both quality and sustainability. It would be my pleasure to bring your vision to life and create unforgettable floral arrangements for your special day.

Please note that this quote may be subject to change depending on any additional requests or changes you may have. Please review the table above and let me know if there is anything missing.

I'm excited about the possibilities your color scheme presents and would be thrilled to bring your floral vision to life! 

Please let me know your thoughts on the quote.

One month before the wedding, we will have a final consultation to review details, confirm the number of items, and settle the remaining balance.

Thank you for considering me as your wedding florist, and I look forward to bringing your vision to life!


Manual payment details
Bank name: Monzo
Account holder: Heather Amor trading as The Flower Kitchen Florist
Account number: 84245856
Sort code: 04-00-04

Hope to hear from you soon.
Kindest,
Heather</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Quote</button>
        </form>

        <?php include_once './app/views/partials/footer.php'; ?>
