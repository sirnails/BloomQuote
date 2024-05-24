<?php include_once './app/views/partials/navbar.php'; ?>

        <h1>Your Quotes</h1>
        <a href="index.php?action=create_quote" class="btn btn-primary mb-3">Create New Quote</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Wedding Date</th>
                    <th>Bride Name</th>
                    <th>Groom Name</th>
                    <th>Total Cost</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($quote = $quotes->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $quote['id']; ?></td>
                        <td><?php echo $quote['wedding_date']; ?></td>
                        <td><?php echo $quote['bride_name']; ?></td>
                        <td><?php echo $quote['groom_name']; ?></td>
                        <td>£<?php echo number_format($quote['total_cost'], 2); ?></td>
                        <td>
                            <a href="index.php?action=show_quote&id=<?php echo $quote['id']; ?>" class="btn btn-info">View</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

<?php include_once './app/views/partials/footer.php'; ?>

