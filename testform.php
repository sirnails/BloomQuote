<?php

session_start();

// Generate CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];
include 'db.php';

include 'include/head.php';
include 'include/navbar.php';
include 'include/sidenav.php';

?>


<!-- HTML -->

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
							
							<form id="myForm">
							  <div class="form-group">
								<label for="name">Name</label>
								<textarea id="name" name="name" class="form-control" onfocus="openModal(this)" readonly></textarea>
							  </div>
							  <div class="form-group">
								<label for="email">Email</label>
								<input type="email" id="email" name="email" class="form-control" onfocus="openModal(this)" readonly>
							  </div>
							  <div class="form-group">
								<label for="password">Password</label>
								<input type="password" id="password" name="password" class="form-control" onfocus="openModal(this)" readonly>
							  </div>
							  <button type="submit" class="btn btn-primary">Submit</button>
							</form>
							<!-- Form -->
							
							<!-- Modal -->
							<div id="myModal" class="modal">
							  <div class="modal-content">
								<span id="closeBtn" class="close">×</span>
								<label id="modalLabel"></label>
								<textarea id="modalInput"></textarea>
							  </div>
							</div>
                        </div>
						
                    </div>
                </main>

<?php include 'include/footer.php'; ?>


<!-- CSS -->
<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
  justify-content: center; /* Center vertically */
  align-items: center; /* Center horizontally */
}


/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 10% 10% 10% auto; /* Adjust margins to avoid overlapping with sidebar */
  padding: 20px;
  border: 1px solid #888;
  max-width: 100%; /* Limit the modal width to avoid overlapping */
  box-sizing: border-box; /* Include padding and border in width calculation */
}


/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

/* The Modal Input */
#modalInput {
  width: 100%;
}
</style>
<script>
/* JavaScript */
// Get the modal
var modal = document.getElementById("myModal");

// Get the close button
var closeBtn = document.getElementById("closeBtn");

// Get the modal label
var modalLabel = document.getElementById("modalLabel");

// Get the modal input
var modalInput = document.getElementById("modalInput");

// Function to open the modal
function openModal(input) {
  console.log("openModal called with:", input);

  closeSidebar();

  // Get the input element
  var inputElement = document.getElementById(input.id);

  // Get the input label
  var inputLabel = document.querySelector("label[for='" + input.id + "']");

  // Set the modal label text to the input label text
  modalLabel.innerHTML = inputLabel.innerHTML;

  // Determine if the input is a textarea or input element
  if (input.tagName === 'TEXTAREA') {
    // For textarea, set the modal textarea value
    modalInput.value = inputElement.value;
  } else {
    // For input, set the modal input value
    modalInput.value = inputElement.value;
  }

  // Display the modal
  modal.style.display = "block";
  
  // Automatically focus on the modal input
  modalInput.focus();
}


// Function to close the modal
function closeModal() {
  // Hide the modal
  modal.style.display = "none";
}

// Function to save the modal input
function saveModalInput() {
  // Get the modal label text
  var labelText = modalLabel.innerHTML;

  // Get the form input element that matches the modal label text
  var formInput = document.querySelector("input[name='" + labelText.toLowerCase() + "'], textarea[name='" + labelText.toLowerCase() + "']");

  if (formInput.tagName === 'TEXTAREA') {
    // For textarea, set the value property
    formInput.value = modalInput.value;
  } else {
    // For input, set the value attribute
    formInput.setAttribute('value', modalInput.value);
  }
}



// Add an event listener to the close button
closeBtn.addEventListener("click", function() {
  // Save the modal input
  saveModalInput();

  // Close the modal
  closeModal();
});

// Add an event listener to the window
window.addEventListener("click", function(event) {
  // If the user clicks anywhere outside of the modal, close it
  if (event.target == modal) {
    // Save the modal input
    saveModalInput();

    // Close the modal
    closeModal();
  }
});

// Function to close the sidebar menu
function closeSidebar() {
  var sidebar = document.getElementById('sidenavAccordion'); // Update with your actual sidebar element ID
  sidebar.classList.remove('show'); // Use 'show' class to close the sidebar
}


</script>