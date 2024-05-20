<?php
session_start();

// Generate CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];
include 'db.php';

if (isset($_GET['view']) && $_GET['view'] === 'archived') {
    $rows = fetchArchivedCustomers($pdo);
} else {
    $rows = fetchActiveCustomers($pdo);
}


// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!hash_equals($csrf, $_POST['csrf'])) {
        die('Invalid CSRF token');
    }

    if ($_POST['action'] == 'add_customer' || isset($_POST['update'])) {
        // Add customer
        $fname = trim($_POST['fname']); 
        $lname = trim($_POST['lname']);
        $phone = trim($_POST['phone']); 
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $address = formatUKAddress(trim($_POST['address']));
        $IsArchived = isset($_POST['IsArchived']) ? 1 : 0;

        if (empty($fname) || empty($lname) || empty($phone) || !$email || empty($address)) {
            die('Invalid input');
        }
		
		 $customerData = array(
			"FirstName" => $fname,
			"LastName" => $lname,
			"PhoneNumber" => $phone,
			"Email" => $email,
			"DeliveryAddress" => $address,
			"IsArchived" => $IsArchived
		); 
		
		if (isset($_POST['update'])) {
			// Update customer
			$CustomerID = $_POST['CustomerID'];
			updateCustomer($pdo, $CustomerID, $customerData);
			header('Location: ' . strtok($_SERVER["REQUEST_URI"],'?'));
			exit();
		} else {
			// Add new customer
			addNewCustomer($pdo, $customerData);
		}
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {

	// Handle GET request for editing a customer
	if (isset($_GET['edit'])) {
		$CustomerID = $_GET['edit'];
		$customer = fetchCustomerDetails($pdo, $CustomerID);
	} 

	if (isset($_GET['id'])) {
		// Delete customer
		deleteCustomer($pdo, $_GET['id']);
		header('Location: ' . strtok($_SERVER["REQUEST_URI"],'?'));
		exit();
	}
}

include 'include/head.php';
include 'include/navbar.php';
include 'include/sidenav.php';
?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">
							<?php 
								if (isset($_GET['view']) && $_GET['view'] === 'archived') {
									echo "Viewing Archived Customers";
								} else {
									echo "Viewing Active Customers";
								}
							?></h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Customers</li>
                        </ol>
						<?php
						$eventDetails = getNextEvent($pdo);
						if ($eventDetails) {
							//var_dump($eventDetails);
							echo "Next Event: ";
							echo "<a href='quotes.php?CustomerID=".$eventDetails['CustomerID']."&EventID=".$eventDetails['EventID']."&action=view'>".$eventDetails['FirstName'] . " " . $eventDetails['LastName']." On: ".$eventDetails['EventDate']." for event: ".$eventDetails['EventType']."</a>";
                //
                //
                //$eventDetails['FirstName']
                //$eventDetails['LastName']
                //
                //$eventDetails['EventType']
				
							foreach ($eventDetails as $key => $value) {
								//echo $key . ": " . $value . "<br>";
							}
						} else {
							echo "No upcoming events found.";
						}
						?>
						
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
									<?php 
										if (isset($_GET['view']) && $_GET['view'] === 'archived') {
											echo "Archived Customers";
										} else {
											echo "Active Customers";
										}
									?>
								</br>
								<a href="?view=active">Show Active Customers</a> | <a href="?view=archived">Show Archived Customers</a>
                            </div>
                            <div class="card-body">
								<div class="overflow-scroll">
									<table id="datatablesSimple" class="table table-bordered">
										<thead>
											<tr>
												<th>View Customer Events</th>
												<th>FirstName</th>
												<th>LastName</th>
												<th>PhoneNumber</th>
												<th>Email</th>
												<th>DeliveryAddress</th>
												<th>Delete Customer</th>
												<th>Edit Customer Details</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>View Customer Events</th>
												<th>CustomerID</th>
												<th>FirstName</th>
												<th>LastName</th>
												<th>PhoneNumber</th>
												<th>Email</th>
												<th>DeliveryAddress</th>
												<th>Delete Customer</th>
												<th>Edit Customer Details</th>
											</tr>
										</tfoot>
										<tbody>
											<?php foreach ($rows as $row): ?>
											<tr>
												<td>
												<a href="events.php?CustomerID=<?= $row['CustomerID'] ?>" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block;">View Events <?= $row['CustomerID'] ?></a>
												</td>
												<td><?= $row['FirstName'] ?></td>
												<td><?= $row['LastName'] ?></td>
												<td><?= $row['PhoneNumber'] ?></td>
												<td><?= $row['Email'] ?></td>
												<td><?= $row['DeliveryAddress'] ?></td>
												
												<td>
													<a href="?id=<?= $row['CustomerID'] ?>" onclick="return confirm('Are you sure you want to delete this customer and all associated information?')">Delete</a>
												</td>
												
												<td>
													<a href="?edit=<?= $row['CustomerID'] ?>">Edit</a>
												</td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
								
								<!-- Form to add new customer -->
								<form action="" method="post">
									<input type="hidden" name="csrf" value="<?= $csrf ?>">
									<!-- <input type="hidden" name="CustomerID" value="<?= isset($customer) ? $customer['CustomerID'] : '' ?>"> -->
									<input type="hidden" name="CustomerID" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>">

									<input type="hidden" name="action" value="add_customer">
									<label for="fname">First Name:</label><br>
									<input type="text" id="fname" name="fname" value="<?= isset($customer) ? $customer['FirstName'] : '' ?>"><br>
									<label for="lname">Last Name:</label><br>
									<input type="text" id="lname" name="lname" value="<?= isset($customer) ? $customer['LastName'] : '' ?>"><br>
									<label for="phone">Phone Number:</label><br>
									<input type="text" id="phone" name="phone" value="<?= isset($customer) ? $customer['PhoneNumber'] : '' ?>"><br>
									<label for="email">Email:</label><br>
									<input type="text" id="email" name="email" value="<?= isset($customer) ? $customer['Email'] : '' ?>"><br>
									<label for="address">Delivery Address:</label><br>
									<input type="text" id="address" name="address" value="<?= isset($customer) ? $customer['DeliveryAddress'] : '' ?>"><br>
									<label for="IsArchived">Archive Customer:</label><br>
									<input type="checkbox" id="IsArchived" name="IsArchived" value="<?= isset($customer) ? $customer['IsArchived'] : '' ?>"><br>
									<input type="submit" value="Add Customer">
									<input type="submit" name="update" value="Update Customer">
								</form>

                            </div>
                        </div>
                    </div>
                </main>
<?php include 'include/footer.php'; ?>
