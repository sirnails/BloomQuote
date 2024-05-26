<?php
$todayDate = date('d-m-Y');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quote - Printable</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .quote-header, .quote-footer {
            margin-bottom: 20px;
        }
        .quote-line-items {
            margin-bottom: 20px;
        }
        .quote-line-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-cost {
            text-align: right;
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .pre-wrap {
            white-space: pre-wrap; /* CSS to preserve whitespace and new lines */
        }
        hr {
            border: 0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="img/logo.png" alt="Company Logo" width="300">
            <p><strong>Quotation Issue Date</strong> <?php echo $todayDate; ?></p>
        </div>
        <div class="quote-header">
            <hr>
            <p><strong>Wedding Date and Time:</strong> <?php echo $quote['wedding_date'] . ' <strong>at:</strong> ' . $quote['time']; ?></p>
            <p><strong>Billing Address:</strong> <?php echo $quote['billing_address']; ?></p>
            <p><strong>Name Bride/Person 1:</strong> <?php echo $quote['bride_name']; ?> <strong>Contact Nº 1:</strong> <?php echo $quote['bride_contact']; ?></p>
            <p><strong>Email Bride/Person 1:</strong> <?php echo $quote['bride_email']; ?></p>
            <p><strong>Name Groom/Person 2:</strong> <?php echo $quote['groom_name']; ?> <strong>Contact Nº 2:</strong> <?php echo $quote['groom_contact']; ?></p>
            <p><strong>Email Groom/Person 2:</strong> <?php echo $quote['groom_email']; ?></p>
            <p><strong>Ceremony/Church Address:</strong> <?php echo $quote['ceremony_address']; ?></p>
            <p><strong>Breakfast/Venue Address:</strong> <?php echo $quote['venue_address']; ?></p>
            <p><strong>Other Address:</strong> <?php echo $quote['other_address']; ?></p> <hr> </div>
        <div class="quote-custom-message pre-wrap"><p><?php echo $quote['custom_message']; ?></p></div>
        <div class="quote-line-items">
            <table>
                <thead>
                    <tr>
                        <th>Details</th>
                        <th>Deliver to</th>
                        <th>Cost per Item</th>
                        <th>Number of Items</th>
                        <th>Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $items->fetch_assoc()) { ?>
                        <tr>
                            <td class="pre-wrap"><?php echo $item['description']; ?></td>
                            <td><?php echo $item['delivery_location']; ?></td>
                            <td>£<?php echo number_format($item['cost_per_item'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>£<?php echo number_format($item['total_cost'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="quote-footer pre-wrap"><p class="total-cost"><strong>Total Cost:</strong> £<?php echo number_format($quote['total_cost'], 2); ?></p>
            </br><strong>Deposit Deadline:</strong> This quote is valid until <?php echo $quote['deposit_date']; ?> if the deposit is not paid before this date it will be available for others to book.</br>
            <p><strong>Payment Terms:</strong> <?php echo $quote['payment_terms']; ?></p>
        </div>
    </div>
</body>
</html>
