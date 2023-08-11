<?php
session_start();

// Generate CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

include 'db.php';

if (!isset($_GET['EventID'])) {
    // no event ID set, go back to customers
    ob_start();
    echo "<h1> Event not found </h1>";
    header('Location: customers.php');
    ob_end_flush();
    exit();
}else if (!isset($_GET['QuoteID'])) {
    // no event ID set, go back to customers
    ob_start();
    echo "<h1> Event not found </h1>";
    header('Location: customers.php');
    ob_end_flush();
    exit();
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!hash_equals($csrf, $_POST['csrf'])) {
        die('Invalid CSRF token');
    }

	if ($_POST['action'] == 'Update Quote' || $_POST['action'] == 'Add Line')  {
    // Update quote
		
		$quoteData = [
		'IntroductionText' => $_POST['introduction_text'],
		'DepositPaid' => $_POST['deposit_paid'],
		'DepositDueDate' => $_POST['deposit_due_date'], 
		'FinalPaymentDueDate' => $_POST['final_payment_due_date'],
		'Notes' => $_POST['notes']
		];
		
		$QuoteID = $_POST['quote_id'];
		
		updateQuote($pdo, $QuoteID, $quoteData);

		if (isset($_POST['order_index'])) {
			foreach ($_POST['order_index'] as $itemID => $orderIndex) {
				$details = $_POST['details'][$itemID];
				$deliverTo = $_POST['deliver_to'][$itemID];
				$costPerItem = $_POST['cost_per_item'][$itemID];
				$numberOfItems = $_POST['number_of_items'][$itemID];
				$cost = $costPerItem * $numberOfItems;

				// This is an existing item. Update it in the database.
				$quoteLineData = [
					'QuoteID' => $QuoteID,
					'Details' => $details,
					'DeliverTo' => $deliverTo,
					'CostPerItem' => $costPerItem,
					'NumberOfItems' => $numberOfItems,
					'Cost' => $cost,
					'OrderIndex' => $orderIndex
				];
				updateQuoteLine($pdo, $itemID, $quoteLineData);
			}
		}

		if ($_POST['action'] == 'Add Line') {
			// This is a new item. Insert it into the database.

			if (isset($_POST['LineNumber'])) {
				$orderIndex = $_POST['LineNumber'];
			}

			$quoteLineData = [
				'QuoteID' => $QuoteID,
				'Details' => 'Detail',
				'DeliverTo' => 'Venue',
				'CostPerItem' => 0.00,
				'NumberOfItems' => 0,
				'Cost' => 0,
				'OrderIndex' => $orderIndex
			];
			addQuoteLine($pdo, $QuoteID, $quoteLineData);
		}
	}

	if ($_POST['action'] == 'swap_rows') {
        // Swap the order of this item and the one above it
        swapOrderIndex($pdo, $_POST['item_id'], $_POST['swap_with_id']);
    }

	if ($_POST['action'] == 'delete_row') {
        // Remove this row
        deleteQuoteLine($pdo, $_POST['delete_id']);
    }


    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit();
}
// Fetch customer
$QuoteID = $_GET['QuoteID'];
$CustomerID = $_GET['CustomerID'];
$EventID = $_GET['EventID'];

$customer = fetchCustomerDetails($pdo, $CustomerID);
$quote = fetchQuote($pdo, $QuoteID); 
$quoteItems = fetchQuoteLines($pdo, $QuoteID);


$eventDetails = fetchEvent($pdo, $EventID);

include 'include/head.php';
include 'include/navbar.php';
include 'include/sidenav.php';
?>

<?php if (isset($EventID) && $_GET['action'] == 'View'): ?>


			<style>
				.even {
					background-color: #E0E0E0;
				}
				.odd {
					background-color: #C0C0C0;
				}
				.changed {
					background-color: red !important; /* Override other background colors */
				}
				#editableText {
					cursor: pointer;
					min-height: 30px;  // or any other appropriate value
					background-color: #f8f9fa;  // light grey, similar to Bootstrap's input background
					padding: 5px;
				}

			</style>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Quotes</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href='index.php'>Customers</a></li>
                            <li class="breadcrumb-item"><a href='events.php?CustomerID=<?= $CustomerID ?>'>Events</a></li>
                            <li class="breadcrumb-item"><a href='quotes.php?CustomerID=<?= $CustomerID ?>&EventID=<?= $EventID ?>'>Quotes</a></li>
                            <li class="breadcrumb-item active">Quote</li>
                        </ol>
						
                        <div
                            <div>
                                <i class="fas fa-table me-1"></i>
                                <?= $firstName = $customer["FirstName"]; ?> <?= $lastName = $customer["LastName"]; ?> 
                            </div>
                            <div>
							




		<!-- Start of the form for editing the quote -->
		<form action="" method="post" data-changed="false" class="container-fluid px-4">
			<div class="form-group">
			<input class="form-control" type="hidden" name="csrf" value="<?= $csrf ?>">
			<input class="form-control" type="hidden" name="quote_id" value="<?= $quote['QuoteID'] ?>">
			<input class="form-control" type="hidden" name="action" value="update_quote">
			
<!-- $eventDetails                                           -->
<!-- array(10) {                                             -->
<!--   ["EventID"]=>                                         -->
<!--   int(48)                                               -->
<!--   ["CustomerID"]=>                                      -->
<!--   int(28)                                               -->
<!--   ["EventDate"]=>                                       -->
<!--   string(10) "2025-05-05"                               -->
<!--   ["ConsultationType"]=>                                -->
<!--   string(7) "Wedding"                                   -->
<!--   ["IsBooked"]=>                                        -->
<!--   int(0)                                                -->
<!--   ["FirstName"]=>                                       -->
<!--   string(6) "Nicole"                                    -->
<!--   ["LastName"]=>                                        -->
<!--   string(5) "Price"                                     -->
<!--   ["PhoneNumber"]=>                                     -->
<!--   string(13) "020 8987 0312"                            -->
<!--   ["Email"]=>                                           -->
<!--   string(22) "Nicole.Price@gmail.com"                   -->
<!--   ["DeliveryAddress"]=>                                 -->
<!--   string(35) "62 Grove Park Rd, Haringey, N15 4SN"      -->

			<table class="table table-hover table-fixed">
				<tr>
					<td>Wedding Date:  </td>
					<td colspan="2"><?php $originalDate = $eventDetails['EventDate']; ?>
					<?php $newDate = date(("jS F, Y"), strtotime($originalDate)); echo $newDate; ?></td>
					<td>Wedding Time: </td>
					<td><?= $eventDetails['IsBooked'] ?></td>
				</tr>
				<tr>
					<td>Billing Address:  </td>
					<td colspan="4"><?= $eventDetails['DeliveryAddress'] ?></td>
				</tr>
				<tr>
					<td colspan="5">Introduction Text: 
					    <p id="editableText" onclick="makeEditable()"><?php
						if (isset($quote['IntroductionText']) && !empty(trim($quote['IntroductionText']))) {
							echo $quote['IntroductionText'];
						} else { 
							echo '[Click to edit text]';
						}
						?> </p>
						<textarea class="form-control" id="textEditor" style="display: none;"></textarea>
						
						<!-- Hidden input to store the value for submission -->
						<input type="hidden" name="introduction_text" id="introduction_text">
				</tr>
				<tr>
					<th scope="col">Details</th>
					<th scope="col">Deliver To</th>
					<th scope="col">Cost Per Item</th>
					<th scope="col">Number of Items</th>
					<th scope="col">Cost</th>
					<th scope="col">Operations</th>
				</tr>
				<?php //foreach ($quoteItems as $index => $item):?>
				<?php for ($i=0; $i<count($quoteItems); $i++): ?>
				<?php $item = $quoteItems[$i]; ?>
				<!-- <?php //foreach ($quoteItems as $item): ?> -->
				<tr class="<?php echo ($i % 2 == 0) ? 'even' : 'odd'; ?>">
				    <input class="form-control" type="hidden" name="order_index[<?= $item['ItemID'] ?>]" value="<?= $i ?>">
					<td><textarea class="form-control"  id="details" name="details[<?= $item['ItemID'] ?>]"><?= $item['Details'] ?></textarea></td>
					<td><input class="form-control" type="text" id="deliver_to" name="deliver_to[<?= $item['ItemID'] ?>]" value="<?= $item['DeliverTo'] ?>"></td>
					<td><input class="form-control" type="number" min="-10000.00" max="10000.00" step="0.01" class="cost_per_item" id="cost_per_item" name="cost_per_item[<?= $item['ItemID'] ?>]" value="<?= $item['CostPerItem'] ?>"></td>
					<td><input class="form-control" type="number" min="0" max="10000" step="1" class="number_of_items" id="number_of_items" name="number_of_items[<?= $item['ItemID'] ?>]" value="<?= $item['NumberOfItems'] ?>"></td>
					<td><input class="form-control" type="number" min="-10000.00" max="10000.00" step="0.01" class="cost_input" id="cost[<?= $item['ItemID'] ?>]" name="cost[<?= $item['ItemID'] ?>]" value="<?= $item['Cost'] ?>"></td>

				<td style="display: flex; align-items: center;"><br>
					<?php //if ($index > 0): ?>
					<?php if ($i > 0): ?>
					<form action="" method="post">
						<input class="form-control" type="hidden" name="csrf" value="<?= $csrf ?>">
						<input class="form-control" type="hidden" name="item_id" value="<?= $item['ItemID'] ?>">
						<input class="form-control" type="hidden" name="swap_with_id" value="<?= $quoteItems[$i-1]['ItemID'] ?>">
						<input class="form-control" type="hidden" name="action" value="swap_rows">
						<input class="form-control" type="submit" value="&#x2191;" title="Move Up" style="font-size: 20px; background: none; border: none; cursor: pointer;"> 
					</form>
					<?php endif; ?>
					<?php if ($i < count($quoteItems) - 1): ?>
					<form action="" method="post">
						<input class="form-control" type="hidden" name="csrf" value="<?= $csrf ?>">
						<input class="form-control" type="hidden" name="item_id" value="<?= $item['ItemID'] ?>">
						<input class="form-control" type="hidden" name="swap_with_id" value="<?= $quoteItems[$i+1]['ItemID'] ?>">
						<input class="form-control" type="hidden" name="action" value="swap_rows">
						<input class="form-control" type="submit" value="&#x2193;" title="Move Down" style="font-size: 20px; background: none; border: none; cursor: pointer;">
					</form>
					<?php endif; ?>
					<form action="" method="post" style="margin-left: auto;">
						<input class="form-control" type="hidden" name="csrf" value="<?= $csrf ?>">
						<input class="form-control" type="hidden" name="delete_id" value="<?= $item['ItemID'] ?>">
						<input class="form-control" type="hidden" name="action" value="delete_row">
						<input class="form-control" type="submit" value="🗑" title="Delete row <?= $i+1 ?>" style="font-size: 20px; background: none; border: none; cursor: pointer;">
					</form>
				</td>

				</tr>
				<?php endfor; ?>
				
				<?php if (count($quoteItems) == 0) { echo '<tr><td colspan="5"> <center> Table Empty, Add rows: </center>  </td></tr>'; } ?>
				<?php  { echo '<tr><td colspan="5"> <button type="submit" class="btn btn-secondary form-control" name="action" value="Add Line">Add Row</button></td></tr>'; } ?>
				<tr>
					<td colspan="5">Deposit Paid: <input class="form-control" type="text" id="deposit_paid" name="deposit_paid" value="<?= $quote['DepositPaid'] ?>"></td>
				</tr>
				<tr>
					<td colspan="5">Deposit Due Date: <input class="form-control" type="date" id="deposit_due_date" name="deposit_due_date" value="<?= $quote['DepositDueDate'] ?>"></td>
				</tr>
				<tr>
					<td colspan="5">Final Payment Due Date: <input class="form-control" type="date" id="final_payment_due_date" name="final_payment_due_date" value="<?= $quote['FinalPaymentDueDate'] ?>"></td>
				</tr>
				<tr>
					<td colspan="5">Notes: <textarea id="notes" name="notes"><?= $quote['Notes'] ?></textarea></td>
				</tr>
			</table>
			<input type="submit" name="action" value="Update Quote">
			<input type="hidden" name="LineNumber" value="<?= count($quoteItems)?>">
			<input type="submit" name="action" value="Add Line">
			</div>
		</form>

<?php endif; ?>




<script>
document.querySelectorAll('.cost_per_item, .number_of_items').forEach(item => {
    item.addEventListener('input', calculateCost);
});

function calculateCost(event) {
    const row = event.target.closest('tr');
    const costPerItem = parseFloat(row.querySelector('.cost_per_item').value);
    const numberOfItems = parseInt(row.querySelector('.number_of_items').value);
    const costInput = row.querySelector('.cost_input');

    if (Number.isFinite(costPerItem) && Number.isInteger(numberOfItems)) {
        const cost = (costPerItem * numberOfItems).toFixed(2);
        costInput.value = cost;
    } else {
        costInput.value = "123";
    }
}


function makeEditable() {
    const textElement = document.getElementById('editableText');
    const textEditor = document.getElementById('textEditor');
    
    // Transfer the text and toggle visibility
    const actualText = textElement.innerText !== '[Click to edit text]' ? textElement.innerText : '';
    textEditor.value = actualText;
    textElement.style.display = 'none';
    textEditor.style.display = 'block';
    
    // Focus the textarea for editing
    textEditor.focus();

    // Handle when the textarea loses focus
    textEditor.onblur = function() {
        const updatedText = textEditor.value.trim();
        textElement.innerText = updatedText || '[Click to edit text]';
        textEditor.style.display = 'none';
        textElement.style.display = 'block';

        // Update hidden input value
        document.getElementById('introduction_text').value = updatedText;
    };
}




</script>



                        </div>
                    </div>
                </main>
<?php include 'include/footer.php'; ?>






