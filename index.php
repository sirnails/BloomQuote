<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php'; // Include database configuration

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use App\Helpers\InputHelper;
use App\Controllers\UserController;
use App\Controllers\QuoteController;

ob_start(); // Start output buffering

/** 
* Starts a PHP session and sets up error reporting.
* Loads the required configuration and controller files.
* Checks if an action parameter is set in the URL and stores it in the $action variable.
* If the user is logged in (i.e. a user_id is set in the session), the code will continue executing.
*/
 
// set_exception_handler(function ($e) {
//     error_log($e->getMessage());
//     http_response_code(500);
//     echo "An unexpected error occurred. Please try again later.";
// });


error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('log_errors', TRUE);

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']), // Secure only if using HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();
session_regenerate_id(true);


// Generate a CSRF token if one does not exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


$action = isset($_GET['action']) ? InputHelper::sanitizeString($_GET['action']) : '';

if (isset($_SESSION['user_id'])) { 
    $userController = new UserController();
    $user = $userController->getUserInfo($_SESSION['user_id']);
    $quoteModel = new Quote();
    $upcomingWeddings = $quoteModel->getNextWeddingGroup($_SESSION['user_id']);

/**
 * Handles the various actions that can be performed on quotes, such as creating, editing, and deleting quotes and quote items.
 * The appropriate QuoteController method is called based on the $action parameter.
 */
switch ($action) {
    case 'logout':
        $controller = new UserController();
        $controller->logout();
        break;
    case 'create_quote':
        $controller = new QuoteController();
        $controller->create();
        break;
    case 'add_item':
        $controller = new QuoteController();
        $controller->add_item();
        break;
    case 'edit_item':
        $controller = new QuoteController();
        $controller->edit_item();
        break;
    case 'edit_quote':
        $controller = new QuoteController();
        $controller->edit_quote();
        break;
    case 'show_quote':
        $controller = new QuoteController();
        $controller->show(InputHelper::sanitizeInt($_GET['id']));
        break;
    case 'view_quotes':
        $controller = new QuoteController();
        $controller->list_quotes();
        break;
    case 'print_quote':
        $controller = new QuoteController();
        $controller->print(InputHelper::sanitizeInt($_GET['id']));
        break;
    case 'move_item_up':
        $controller = new QuoteController();
        $controller->move_item_up();
        break;
    case 'move_item_down':
        $controller = new QuoteController();
        $controller->move_item_down();
        break;
    case 'delete_item':
        $controller = new QuoteController();
        $controller->delete_item();
        break;
    case 'delete_quote':
        $controller = new QuoteController();
        $controller->deleteAllQuoteItems();
        $controller->deleteQuote();
        break;
    case 'search_quotes':
        $controller = new QuoteController();
        $controller->search_quotes();
        break;
    case 'record_payment':
        $controller = new QuoteController();
        $controller->record_payment();
        break;
    case 'view_receipt':
        $controller = new QuoteController();
        $controller->view_receipt(InputHelper::sanitizeInt($_GET['id']));
        break;
    case 'initial_payment_input':
        $controller = new QuoteController();
        $controller->initial_payment_input();
        break;
    default:
        include_once './app/views/home.php';
        break;
    }
} else {
    switch ($action) {
        case 'register':
            $controller = new UserController();
            $controller->register();
            break;
        case 'login':
            $controller = new UserController();
            $controller->login();
            break;
        default:
            include_once './app/views/landing.php';
            break;
        }
}
ob_end_flush(); // Flush the buffer at the end
?>