<?php
session_start();

// Generate CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

// Initialize the failed attempts counter
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
    // Validate CSRF token
    if (!hash_equals($csrf, $_POST['csrf'])) {
        die('Invalid CSRF token');
    }
	
	$hardcoded_password = "flower";  // Change to your desired password

	if (isset($_POST['password'])) {
		if ($_POST['password'] === $hardcoded_password) {
			$_SESSION['authenticated'] = true;
			$_SESSION['failed_attempts'] = 0;

			header("Location: /index.php");
			exit();
		} else {
			$_SESSION['authenticated'] = false;
			$_SESSION['failed_attempts']++;  // Increment the failed attempts counter
			$possible_error_messages = [
				"Oops! That password's not right.",
				"Hmm, try a different password.",
				"That password didn't seem to work.",
				"Incorrect password. Give it another shot.",
				"That password had no luck! Try another."
			];

			$random_error = $possible_error_messages[array_rand($possible_error_messages)];
			$error_message = $random_error . "<br>Attempt: " . $_SESSION['failed_attempts'];

		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form action="" method="post">
                                            <div class="form-floating mb-3" hidden>
                                                <input class="form-control" id="inputEmail" type="email" placeholder="name@example.com" disabled />
                                                <label for="inputEmail">Email address</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" required />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary">Login</button>
                                            </div>
                                            <input type="hidden" name="csrf" value="<?php echo $csrf; ?>" /> <!-- CSRF token -->

                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <?php if (!empty($error_message)): ?>
											<div class="small"> <?= $error_message; ?> </div>
										<?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; BloomQuote 2024</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
