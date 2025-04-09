
<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Autoload models and controllers
function autoload($class) {
    if (file_exists("controllers/{$class}.php")) {
        require_once "controllers/{$class}.php";
    } elseif (file_exists("models/{$class}.php")) {
        require_once "models/{$class}.php";
    }
}
spl_autoload_register('autoload');

// Routing
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'Home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerName = ucfirst($controller) . 'Controller';

if (class_exists($controllerName)) {
    $controllerObj = new $controllerName();
    if (method_exists($controllerObj, $action)) {
        $controllerObj->$action();
    } else {
        // Action not found
        header('HTTP/1.1 404 Not Found');
        include 'views/layouts/404.php';
    }
} else {
    // Controller not found
    header('HTTP/1.1 404 Not Found');
    include 'views/layouts/404.php';
}