<?php include_once './app/views/partials/navbar.php'; ?>

<h1>Welcome to BloomQuote!</h1>
<p>Your one-stop solution for managing floral event quotes and details.</p>

<?php if (isset($upcomingWeddings) && count($upcomingWeddings) > 0): ?>
    <?php
        $displayWeddings = [];
        foreach ($upcomingWeddings as $date => $weddings) {
            $displayWeddings = $weddings;
            break;
        }
    ?>
    <?php foreach ($displayWeddings as $wedding): ?>
        <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
            <div class="card-header"><?php echo htmlspecialchars($wedding['wedding_date']); ?></div>
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($wedding['bride_name']); ?> & <?php echo htmlspecialchars($wedding['groom_name']); ?></h5>
                <p class="card-text"><strong>Billing Address:</strong> <?php echo htmlspecialchars($wedding['billing_address']); ?><br><strong>Venue Address:</strong> <?php echo htmlspecialchars($wedding['venue_address']); ?><br><strong>Total Cost:</strong> £<?php echo number_format($wedding['total_cost'], 2); ?></p>
                <a href="index.php?action=show_quote&id=<?php echo $wedding['id']; ?>" class="btn btn-light">View Quote</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No upcoming weddings found.</p>
<?php endif; ?>

<?php include_once './app/views/partials/footer.php'; ?>