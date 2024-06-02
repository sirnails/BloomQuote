<?php include_once './app/views/partials/navbar.php'; ?>

<h1>Payment Receipt</h1>
<p><strong>Amount Paid:</strong> £<?php echo number_format($payment['amount_paid'], 2); ?></p>
<p><strong>Outstanding Balance:</strong> £<?php echo number_format($payment['outstanding_balance'], 2); ?></p>
<p><strong>Due Date:</strong> <?php echo $payment['due_date']; ?></p>
<p><strong>Consultation Date:</strong> <?php echo $payment['consultation_date']; ?></p>
<p><strong>Thank You Message:</strong> <?php echo nl2br(htmlspecialchars($payment['thank_you_message'])); ?></p>

<?php include_once './app/views/partials/footer.php'; ?>