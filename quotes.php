<?php
// Session handle stuff
session_start();
//var_dump($_POST);

// Generate CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

include 'db.php';

if (!isset($_GET['EventID'])) {
	// no event ID set, go back to customers
	header("Location: /index.php");
	exit();
} else {
	$EventID = $_GET['EventID'];
	$quoteIDs = fetchQuoteIDs($pdo, $EventID);
}

if (!isset($_GET['CustomerID'])) {
	// no event ID set, go back to customers
	header("Location: /index.php");
	exit();
} else {
	$CustomerID = $_GET['CustomerID'];
	$customer = fetchCustomerDetails($pdo, $CustomerID);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!hash_equals($csrf, $_POST['csrf'])) {
        die('Invalid CSRF token');
    }
	
	// Check if the user wants to create a new quote
	if (isset($_POST['create'])) { 

		$quoteData = array(
			"IntroductionText" =>  $_POST['IntroductionText'],
			"DepositPaid" => $_POST['DepositPaid'],
			"DepositDueDate" => $_POST['DepositDueDate'],
			"FinalPaymentDueDate" => $_POST['FinalPaymentDueDate'],
			"Notes" => $_POST['Notes']
		); 
		
		addQuote($pdo, $EventID, $quoteData);
		
		// Parse the current URL and its query string
		$urlComponents = parse_url($_SERVER["REQUEST_URI"]);
		parse_str($urlComponents['query'], $params);

		// Remove the action parameter
		//unset($params['action']);
		//unset($params['QuoteID']);

		// Build the URL back
		$newQueryString = http_build_query($params);
		$cleanedURL = $urlComponents['path'] . '?' . $newQueryString;

		header("Location: " . $cleanedURL);
		exit();
	}
	
	// Check if the user wants to update a quote
	if (isset($_POST['update'])) {
		$quoteID = $_POST['QuoteID'];
		// ... other fields ...

		//updateQuote($pdo, $quoteID);  // Assuming updateQuote() handles the updating of a quote
	}

	// Check if the user wants to delete a quote
	if (isset($_GET['delete'])) {
		$quoteID = $_GET['QuoteID'];

		//deleteQuote($pdo, $quoteID);  // Assuming deleteQuote() handles the deletion of a quote
	}

} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {

	// Handle GET requests
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
		
		$QuoteID = (isset($_GET['QuoteID']) ? $_GET['QuoteID'] : '');
		
		if (isset($QuoteID)) {
			if ($action == 'Edit') {
				$EditQuoteDetails = fetchQuote($pdo, $QuoteID);
			}
			elseif ($action == 'Delete') {
				deleteQuote($pdo, $QuoteID);
						
				// Parse the current URL and its query string
				$urlComponents = parse_url($_SERVER["REQUEST_URI"]);
				parse_str($urlComponents['query'], $params);

				// Remove the action parameter
				unset($params['action']);
				unset($params['QuoteID']);

				// Build the URL back
				$newQueryString = http_build_query($params);
				$cleanedURL = $urlComponents['path'] . '?' . $newQueryString;

				header("Location: " . $cleanedURL);
				exit();
			}
		}
		
	}

}

$eventDetails = fetchEvent($pdo, $EventID);

// array(10) {
//   ["EventID"]=>
//   int(48)
//   ["CustomerID"]=>
//   int(28)
//   ["EventDate"]=>
//   string(10) "2025-05-05"
//   ["EventType"]=>
//   string(7) "Wedding"
//   ["IsBooked"]=>
//   int(0)
//   ["FirstName"]=>
//   string(6) "Nicole"
//   ["LastName"]=>
//   string(5) "Price"
//   ["PhoneNumber"]=>
//   string(13) "020 8987 0312"
//   ["Email"]=>
//   string(22) "Nicole.Price@gmail.com"
//   ["DeliveryAddress"]=>
//   string(35) "62 Grove Park Rd, Haringey, N15 4SN"
// }

?>
<?php include 'include/head.php';?>
<?php include 'include/navbar.php';?>
<?php include 'include/sidenav.php';?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4"><?= $eventDetails['EventType'] ?> <?= $eventDetails['EventDate'] ?> Quotes</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href='index.php'>Customers</a></li>
                            <li class="breadcrumb-item"><a href='events.php?CustomerID=<?= $CustomerID ?>'>Events</a></li>
                            <li class="breadcrumb-item active">Quotes</li>
                        </ol>
						
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                <?= $firstName = $customer["FirstName"]; ?> <?= $lastName = $customer["LastName"]; ?> 
								
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
										<tr>
											<th>Detail</th>
											<th>Introduction Text</th>
											<th>Deposit Paid</th>
											<th>Deposit Due Date</th>
											<th>Final Payment Due Date</th>
											<th>Notes</th>
											<th>Edit</th>
											<th>Delete</th>
										</tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
											<th>Detail</th>
											<th>Introduction Text</th>
											<th>Deposit Paid</th>
											<th>Deposit Due Date</th>
											<th>Final Payment Due Date</th>
											<th>Notes</th>
											<th>Edit</th>
											<th>Delete</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
											<?php foreach($quoteIDs as $id) { 
											$quote = fetchQuote($pdo, $id); 
											$QuoteARG = htmlspecialchars($quote['QuoteID'], ENT_QUOTES, 'UTF-8')               ;
											$EventARG = $EventID                                                               ;
											$IntroARG = htmlspecialchars($quote['IntroductionText'], ENT_QUOTES, 'UTF-8')      ;
											$DeposARG = htmlspecialchars($quote['DepositPaid'], ENT_QUOTES, 'UTF-8')           ;
											$DueDaARG = htmlspecialchars($quote['DepositDueDate'], ENT_QUOTES, 'UTF-8')        ;
											$EndDaARG = htmlspecialchars($quote['FinalPaymentDueDate'], ENT_QUOTES, 'UTF-8')   ;
											$NotesARG = htmlspecialchars($quote['Notes'], ENT_QUOTES, 'UTF-8')                 ;
											?>
										 <tr> 
										<td>
										
											<a href='quote.php?QuoteID=<?=$QuoteARG
												. "&EventID=" . $EventID
												. "&CustomerID=" . $CustomerID
												. "&action=View" ?>'
												 style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block;">Go To Detail <?=$QuoteARG?></a>
										</td>
										 <td>  <?= $IntroARG ?> </td>
										 <td>  <?= $DeposARG ?> </td>
										 <td>  <?= $DueDaARG ?> </td>
										 <td>  <?= $EndDaARG ?> </td>
										 <td>  <?= $NotesARG ?> </td>


										<td>
											<a href='quotes.php?QuoteID=<?=$QuoteARG
												. "&EventID=" . $EventID
												. "&CustomerID=" . $CustomerID
												. "&action=Edit" ?>'>
												Edit Overview <?=$QuoteARG?>
											</a>
										</td>



										<td>
											<a href='quotes.php?QuoteID=<?=$QuoteARG
												. "&EventID=" . $EventID
												. "&CustomerID=" . $CustomerID
												. "&action=Delete" ?>'>
												Delete <?=$QuoteARG?>
											</a>
										</td>

											<?php echo '</tr>'; } ?>
										
                                    </tbody>
                                </table>
								
									<form method="post" action="">
										<input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
										<input type="hidden" name="QuoteID" value="<?= htmlspecialchars(isset($EditQuoteDetails) ? $EditQuoteDetails['QuoteID'] : '') ?>">
										<input type="hidden" name="EventID" value="<?= htmlspecialchars(isset($EditQuoteDetails) ? $EditQuoteDetails['EventID'] : '') ?>">

										<label for="IntroductionText">Introduction Text:</label><br>
										<input type="text" name="IntroductionText" id="IntroductionText" value="<?= htmlspecialchars(isset($EditQuoteDetails) ? $EditQuoteDetails['IntroductionText'] : '') ?>"><br><br>

										<label for="DepositPaid">Deposit Paid:</label><br>
										<input type="number" min="0.00" step="0.01" name="DepositPaid" id="DepositPaid" value="<?= htmlspecialchars(isset($EditQuoteDetails) ? $EditQuoteDetails['DepositPaid'] : '') ?>"><br><br>

										<label for="DepositDueDate">Deposit Due Date:</label><br>
										<input type="date" name="DepositDueDate" id="DepositDueDate" value="<?= htmlspecialchars(isset($EditQuoteDetails) ? $EditQuoteDetails['DepositDueDate'] : '') ?>"><br><br>

										<label for="FinalPaymentDueDate">Final Payment Due:</label><br>
										<input type="date" name="FinalPaymentDueDate" id="FinalPaymentDueDate" value="<?= htmlspecialchars(isset($EditQuoteDetails) ? $EditQuoteDetails['FinalPaymentDueDate'] : '') ?>"><br><br>

										<label for="Notes">Notes:</label><br>
										<input type="text" name="Notes" id="Notes" value="<?= htmlspecialchars(isset($EditQuoteDetails) ? $EditQuoteDetails['Notes'] : '') ?>"><br><br>

										<?= (isset($_GET['action']) && $_GET['action'] == 'Edit') ? '<input type="submit" name="update" value="Update Quote">' : '<input type="submit" name="create" value="Create Quote">' ?>

									</form>

                            </div>
                        </div>
                    </div>
                </main>
<?php include 'include/footer.php'; ?>


