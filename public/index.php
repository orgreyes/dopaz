<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\MenuController;
use Controllers\AppController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

// $router->get('/', [MenuController::class,'index']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
