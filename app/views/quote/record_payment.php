<?php include_once './app/views/partials/navbar.php'; ?>

<h1>Record Payment</h1>
<form method="POST" action="index.php?action=record_payment">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="hidden" name="quote_id" value="<?php echo $quote['id']; ?>">
    <input type="hidden" name="quote_item_id" value="<?php echo $quote_item_id; ?>">
    <input type="hidden" name="stage" value="final">
    <div class="form-group">
        <label for="quote_item_id">Quote Item</label>
        <select class="form-control" id="quote_item_id" name="quote_item_id" required>
            <option value="<?php echo $quote_item_id; ?>">Payment</option>
        </select>
    </div>
    <div class="form-group">
        <label for="amount_paid">Amount Paid</label>
        <input type="number" class="form-control" id="amount_paid" name="amount_paid" value="<?php echo $amount_paid; ?>" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="outstanding_balance">Outstanding Balance</label>
        <input type="number" class="form-control" id="outstanding_balance" name="outstanding_balance" value="<?php echo $outstanding_balance; ?>" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="due_date">Due Date</label>
        <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo $due_date; ?>" required>
    </div>
    <div class="form-group">
        <label for="consultation_date">Consultation Date</label>
        <input type="date" class="form-control" id="consultation_date" name="consultation_date" value="<?php echo $consultation_date; ?>" required>
    </div>
    <div class="form-group">
        <label for="thank_you_message">Thank You Message</label>
        <textarea class="form-control" id="thank_you_message" name="thank_you_message" required><?php echo htmlspecialchars($thank_you_message); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Record Payment</button>
</form>

<?php include_once './app/views/partials/footer.php'; ?>
