<?php include_once './app/views/partials/navbar.php'; ?>

<h1>Enter Payment Amount</h1>
<form method="POST" action="index.php?action=record_payment">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="hidden" name="quote_id" value="<?php echo $quote['id']; ?>">
    <input type="hidden" name="stage" value="initial">
    <div class="form-group">
        <label for="amount_paid">Amount Paid</label>
        <input type="number" class="form-control" id="amount_paid" name="amount_paid" step="0.01" required>
    </div>
    <button type="submit" class="btn btn-primary">OK</button>
    <a href="index.php?action=show_quote&id=<?php echo $quote['id']; ?>" class="btn btn-secondary">Cancel</a>
</form>

<?php include_once './app/views/partials/footer.php'; ?>
