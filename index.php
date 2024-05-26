<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once './config/database.php';
    require_once './app/controllers/UserController.php';
    require_once './app/controllers/QuoteController.php';
} catch (Exception $e) {
    echo 'Error: ',  $e->getMessage(), "\n";
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

if (isset($_SESSION['user_id'])) { 

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
        $controller->show($_GET['id']);
        break;
    case 'view_quotes':
        $controller = new QuoteController();
        $controller->list_quotes();
        break;
    case 'print_quote':
        $controller = new QuoteController();
        $controller->print($_GET['id']);
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
?>
