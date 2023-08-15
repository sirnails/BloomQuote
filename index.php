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
		
		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
		deleteCustomer($pdo, $id);
		//deleteCustomer($pdo, $_GET['id']);
		header('Location: ' . strtok($_SERVER["REQUEST_URI"],'?'));
		exit();
	}
}

include 'include/head.php';
include 'include/navbar.php';
include 'include/sidenav.php';
$eventDetails = getNextEvent($pdo);
?>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Warning Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Next Event: </br><?= $eventDetails['FirstName'] . " " . $eventDetails['LastName']." </br> On: ".$eventDetails['EventDate']." </br>for event: ".$eventDetails['EventType'] ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="<?php
						if ($eventDetails) {
							echo "quotes.php?CustomerID=".$eventDetails['CustomerID']."&EventID=".$eventDetails['EventID']."&action=view";
						} else {
							echo "No upcoming events found.";
						}
						?>">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Danger Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
							
							<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">...</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
							
                        </div>
						
                    </div>
                </main>
<?php include 'include/footer.php'; ?>
