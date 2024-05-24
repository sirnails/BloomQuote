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
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">BloomQuote</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=create_quote">Create New Quote</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=view_quotes">View Existing Quotes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=logout">Logout</a>
                </li>
            </ul>
        </div>
        <div class="ml-auto">
            <button class="btn btn-outline-secondary toggle-dark-mode">Toggle Dark Mode</button>
        </div>
    </nav>
    <div class="container mt-4">
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const toggleDarkModeButton = document.querySelector('.toggle-dark-mode');
            
            // Check localStorage for dark mode preference
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }

            toggleDarkModeButton.addEventListener('click', () => {
                document.body.classList.toggle('dark-mode');

                // Save dark mode preference to localStorage
                if (document.body.classList.contains('dark-mode')) {
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    localStorage.setItem('darkMode', 'disabled');
                }
            });
        });
    </script>
