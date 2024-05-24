<?php include_once './app/views/partials/navbar.php'; ?>

        <h1>Welcome to BloomQuote!</h1>
        <p>Your one-stop solution for managing floral event quotes and details.</p>
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="index.php?action=create_quote" class="btn btn-primary">Create New Quote</a>
            <a href="index.php?action=view_quotes" class="btn btn-secondary">View Existing Quotes</a>
            <a href="index.php?action=logout" class="btn btn-danger">Logout</a>
        </div>
    </div>
    
    <?php include_once './app/views/partials/footer.php'; ?>

