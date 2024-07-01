<?php
$todayDate = date('d-m-Y');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Receipt - Printable</title>
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
        .receipt-header, .Receipt-footer {
            margin-bottom: 20px;
        }
        .Receipt-line-items {
            margin-bottom: 20px;
        }
        .Receipt-line-item {
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
            <p><strong>Receipt Issue Date</strong> <?php echo $todayDate; ?></p>
        </div>
        <div class="receipt-header">
        <h1>Payment Receipt</h1>
        <p><strong>Amount Paid:</strong> £<?php echo number_format($payment['amount_paid'], 2); ?></p>
        <p><strong>Outstanding Balance:</strong> £<?php echo number_format($payment['outstanding_balance'], 2); ?></p>
        <p><strong>Final Consultation:</strong> <?php echo date('F Y', strtotime($payment['consultation_date'])); ?></p>
        <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($payment['thank_you_message'])); ?></p>
    </div>
</body>
</html>