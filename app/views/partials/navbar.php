<?php
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use App\Helpers\InputHelper;
use App\Controllers\UserController;
use App\Controllers\QuoteController;

$user = null;
if (isset($_SESSION['user_id'])) { 
    $userController = new UserController();
    $user = $userController->getUserInfo($_SESSION['user_id']);
    $settings = $userController->getUserSettings($_SESSION['user_id']);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>BloomQuote</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dark-mode.css">
    <link rel="stylesheet" href="css/custom.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <style>
        .toggle-dark-mode {
            cursor: pointer;
        }
    </style>
</head>

<body class="<?php echo isset($settings['dark_mode']) && $settings['dark_mode'] ? 'dark-mode' : ''; ?>">


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">BloomQuote</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <?php if (isset($_SESSION['user_id'])) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=create_quote">Create New Quote</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=view_quotes">View Existing Quotes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=settings">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=logout">Logout <?php echo htmlspecialchars($user['username'] ?? ''); ?></a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <!-- <div class="ml-auto">
        <button class="btn btn-outline-secondary toggle-dark-mode">Toggle Dark Mode</button>
    </div> -->
</nav>
<div class="container mt-4">
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const toggleDarkModeButton = document.querySelector('.toggle-dark-mode');
        
        // Check localStorage for dark mode preference
        //if (localStorage.getItem('darkMode') === 'enabled' || "<?php echo isset($settings['dark_mode']) && $settings['dark_mode']; ?>") {
        if ("<?php echo isset($settings['dark_mode']) && $settings['dark_mode']; ?>") {
            document.body.classList.add('dark-mode');
        }

        // toggleDarkModeButton.addEventListener('click', () => {
        //     document.body.classList.toggle('dark-mode');

        //     Save dark mode preference to localStorage
        //     if (document.body.classList.contains('dark-mode')) {
        //         localStorage.setItem('darkMode', 'enabled');
        //     } else {
        //         localStorage.setItem('darkMode', 'disabled');
        //     }
        // });
    });
</script>
