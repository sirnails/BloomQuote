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
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($quote = $quotes->fetch_assoc()) { ?>
            <tr class="clickable-row" data-href="index.php?action=show_quote&id=<?php echo $quote['id']; ?>">
                <td><?php echo $quote['id']; ?></td>
                <td><?php echo $quote['wedding_date']; ?></td>
                <td><?php echo $quote['bride_name']; ?></td>
                <td><?php echo $quote['groom_name']; ?></td>
                <td>£<?php echo number_format($quote['total_cost'], 2); ?></td>
                <td><a href="index.php?action=show_quote&id=<?php echo $quote['id']; ?>" class="btn btn-primary">View Quote</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<style>
    .clickable-row {
        cursor: pointer;
        transition: filter 0.2s;
    }

    /* .clickable-row:hover {
        filter: brightness(50%);
    }
    body.dark-mode .clickable-row:hover {
        filter: brightness(150%);
    }
    body:not(.dark-mode) .clickable-row:hover {
        filter: brightness(50%);
    } */

    .table-striped tbody tr:nth-of-type(odd):hover {
        background-color: #C0C0C0;
    }
    .table-striped tbody tr:nth-of-type(even):hover {
        background-color: #C0C0C0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', () => {
                window.location.href = row.dataset.href;
            });
        });
    });
</script>

<?php include_once './app/views/partials/footer.php'; ?>