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
    $Event_date = $_POST['Event_date'];
    $Event_type = $_POST['Event_type'];
	$is_booked = isset($_POST['is_booked']) ? true : false;

    if ($_POST['action'] == 'add_Event') {
        // Add Event
		addNewEvent($pdo, $CustomerID, $Event_date, $Event_type, $is_booked);
    } elseif ($_POST['action'] == 'update_Event') {
        // Update Event
        $EventID = $_POST['EventID'];
		$EventData = array(
			"EventType" => $Event_type,
			"IsBooked" => $is_booked,
			"EventDate" => $Event_date
		);
		updateEvent($pdo, $EventID, $EventData);
    } elseif ($_POST['action'] == 'delete_Event') {
        // Delete Event
        $EventID = $_POST['EventID'];
		deleteEventWithQuotes($pdo, $EventID);
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit();
}

if(isset($_GET['CustomerID'])) {
    $CustomerID = $_GET['CustomerID'];
	$Events = fetchEventsForCustomer($pdo, $CustomerID);
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
						
						

<?php if (!isset($CustomerID)) {
$results = fetchAllEvents($pdo); ?>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
							</div>
							<div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
										<tr>
											<th>View Quotes</th>
											<th>First Name</th>
											<th>Last Name</th>
											<th>EventDate</th>
											<th>EventType</th>
											<th>IsBooked</th>
											<th>Edit Event</th>
										</tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
											<th>View Quotes</th>
						<th>Customer Name</th>
						<th>Event Date</th>
						<th>Event Type</th>
											<th>Edit Event</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
										<?php foreach ($results as $Event): ?>
										<tr>
											<td>
												<a href="quotes.php?CustomerID=<?= $Event['CustomerID'] ?>&EventID=<?= $Event['EventID'] ?>&action=view" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block;">View Quotes <?= $Event['EventID'] ?></a>
											</td>
											<td><?= $Event['FirstName'] ?></td>
											<td><?= $Event['LastName'] ?></td>
											<td><?= $Event['EventDate'] ?></td>
											<td><?= $Event['EventType'] ?></td>
											<td><?= $Event['IsBooked'] ? 'Yes' : 'No' ?></td>
											<td>
												<button onclick="updateForm(<?= $Event['EventID'] ?>, '<?= $Event['EventDate'] ?>', '<?= $Event['EventType'] ?>', <?= $Event['IsBooked'] ?>)">Edit</button>
											</td>
										</tr>
										<?php endforeach; ?>
                                    </tbody>
                                </table>
<?php } else { ?>


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
											<th>EventDate</th>
											<th>EventType</th>
											<th>IsBooked</th>
											<th>Edit Event</th>
										</tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
											<th>View Quotes</th>
											<th>EventDate</th>
											<th>EventType</th>
											<th>IsBooked</th>
											<th>Edit Event</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
										<?php foreach ($Events as $Event): ?>
										<tr>
											<td>
												<a href="quotes.php?CustomerID=<?= $CustomerID ?>&EventID=<?= $Event['EventID'] ?>&action=view" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block;">View Quotes <?= $Event['EventID'] ?></a>
											</td>
											<td><?= $Event['EventDate'] ?></td>
											<td><?= $Event['EventType'] ?></td>
											<td><?= $Event['IsBooked'] ? 'Yes' : 'No' ?></td>
											<td>
												<button onclick="updateForm(<?= $Event['EventID'] ?>, '<?= $Event['EventDate'] ?>', '<?= $Event['EventType'] ?>', <?= $Event['IsBooked'] ?>)">Edit</button>
											</td>
										</tr>
										<?php endforeach; ?>
                                    </tbody>
                                </table>
								
<?php }
$customer_id = filter_input(INPUT_GET, 'CustomerID', FILTER_VALIDATE_INT);
$customer_id_show = ($customer_id) ? $customer_id : '';
?>
								<!-- Form to add/update/delete an Event -->
								<form action="" method="post">
									<input class="form-control" type="hidden" name="csrf" value="<?= $csrf ?>">
									<input class="form-control" type="hidden" name="CustomerID" value="<?= $customer_id_show; ?>">
									<input class="form-control" type="hidden" name="action" id="action">
									<input class="form-control" type="hidden" name="EventID" id="EventID">
									<label for="Event_date">Event Date:</label><br>
									<input class="form-control" type="date" id="Event_date" name="Event_date"><br>
									<label for="Event_type">Event Type:</label><br>
									<input class="form-control" type="text" id="Event_type" name="Event_type"><br>
									<label for="is_booked">Is Booked:</label><br>
									<input type="checkbox" class="form-check-input" id="is_booked" name="is_booked"><br><br>
									<input type="submit" value="Add Event" onclick="document.getElementById('action').value='add_Event'">
									<input type="submit" value="Update Event" onclick="document.getElementById('action').value='update_Event'">
									<input type="submit" value="Delete Event" onclick="document.getElementById('action').value='delete_Event'">
								</form>

                            </div>
                        </div>
                    </div>
                </main>
				
<script>
function updateForm(EventID, Event_date, Event_type, is_booked) {
    document.getElementById('EventID').value = EventID;
    document.getElementById('Event_date').value = Event_date;
    document.getElementById('Event_type').value = Event_type;
    document.getElementById('is_booked').checked = is_booked;
}
</script>
<?php include 'include/footer.php'; ?>

	

