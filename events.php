<?php
session_start();

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

include 'db.php';

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!hash_equals($csrf, $_POST['csrf'])) {
        die('Invalid CSRF token');
    }

    $CustomerID = $_POST['CustomerID'];
    $event_date = $_POST['event_date'];
    $consultation_type = $_POST['consultation_type'];
	$is_booked = isset($_POST['is_booked']) ? true : false;

    if ($_POST['action'] == 'add_event') {
        // Add event
		addNewEvent($pdo, $CustomerID, $event_date, $consultation_type, $is_booked);
    } elseif ($_POST['action'] == 'update_event') {
        // Update event
        $EventID = $_POST['EventID'];
		$eventData = array(
			"ConsultationType" => $consultation_type,
			"IsBooked" => $is_booked,
			"EventDate" => $event_date
		);
		updateEvent($pdo, $EventID, $eventData);
    } elseif ($_POST['action'] == 'delete_event') {
        // Delete event
        $EventID = $_POST['EventID'];
		deleteEventWithQuotes($pdo, $EventID);
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit();
}

if(isset($_GET['CustomerID'])) {
    $CustomerID = $_GET['CustomerID'];
	$events = fetchEventsForCustomer($pdo, $CustomerID);
	$customer = fetchCustomerDetails($pdo, $CustomerID);
}
	
include 'include/head.php';
include 'include/navbar.php';
include 'include/sidenav.php';
?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Events</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href='index.php'>Customers</a></li>
                            <li class="breadcrumb-item active">Events</li>
                        </ol>
						

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Customer: <?= $firstName = $customer["FirstName"]; ?> <?= $lastName = $customer["LastName"]; ?>
								<p> Phone Number: <a href="tel:<?= $phoneNumber = $customer["PhoneNumber"]; ?>"><?= $phoneNumber = $customer["PhoneNumber"]; ?></a></p>
								<p> Email: <a href="mailto:<?= $email = $customer["Email"]; ?>"><?= $email = $customer["Email"]; ?></a></p>
								<p> Google Maps to Address: <a href="https://www.google.com/maps/place/<?= $deliveryAddress = $customer["DeliveryAddress"]; ?>"><?= $deliveryAddress = $customer["DeliveryAddress"]; ?></a></p>
								<p> Waze to Address: <a href="https://waze.com/ul?q=<?= $deliveryAddress = $customer["DeliveryAddress"]; ?>"><?= $deliveryAddress = $customer["DeliveryAddress"]; ?></a></p>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
										<tr>
											<th>View Quotes</th>
											<th>EventID</th>
											<th>EventDate</th>
											<th>ConsultationType</th>
											<th>IsBooked</th>
											<th>Edit Event</th>
										</tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
											<th>View Quotes</th>
											<th>EventID</th>
											<th>EventDate</th>
											<th>ConsultationType</th>
											<th>IsBooked</th>
											<th>Edit Event</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
										<?php foreach ($events as $event): ?>
										<tr>
											<td>
												<a href="quotes.php?CustomerID=<?= $CustomerID ?>&EventID=<?= $event['EventID'] ?>&action=view" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block;">View Quotes <?= $event['EventID'] ?></a>
											</td>
											<td><?= $event['EventID'] ?></td>
											<td><?= $event['EventDate'] ?></td>
											<td><?= $event['ConsultationType'] ?></td>
											<td><?= $event['IsBooked'] ? 'Yes' : 'No' ?></td>
											<td>
												<button onclick="updateForm(<?= $event['EventID'] ?>, '<?= $event['EventDate'] ?>', '<?= $event['ConsultationType'] ?>', <?= $event['IsBooked'] ?>)">Edit</button>
											</td>
										</tr>
										<?php endforeach; ?>
                                    </tbody>
                                </table>
								
								<!-- Form to add/update/delete an event -->
								<form action="" method="post">
									<input class="form-control" type="hidden" name="csrf" value="<?= $csrf ?>">
									<input class="form-control" type="hidden" name="CustomerID" value="<?= $_GET['CustomerID'] ?>">
									<input class="form-control" type="hidden" name="action" id="action">
									<input class="form-control" type="hidden" name="EventID" id="EventID">
									<label for="event_date">Event Date:</label><br>
									<input class="form-control" type="date" id="event_date" name="event_date"><br>
									<label for="consultation_type">Consultation Type:</label><br>
									<input class="form-control" type="text" id="consultation_type" name="consultation_type"><br>
									<label for="is_booked">Is Booked:</label><br>
									<input type="checkbox" class="form-check-input" id="is_booked" name="is_booked"><br><br>
									<input type="submit" value="Add Event" onclick="document.getElementById('action').value='add_event'">
									<input type="submit" value="Update Event" onclick="document.getElementById('action').value='update_event'">
									<input type="submit" value="Delete Event" onclick="document.getElementById('action').value='delete_event'">
								</form>

                            </div>
                        </div>
                    </div>
                </main>
				
<script>
function updateForm(EventID, event_date, consultation_type, is_booked) {
    document.getElementById('EventID').value = EventID;
    document.getElementById('event_date').value = event_date;
    document.getElementById('consultation_type').value = consultation_type;
    document.getElementById('is_booked').checked = is_booked;
}
</script>
<?php include 'include/footer.php'; ?>

	

