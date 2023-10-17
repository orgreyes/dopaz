<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\MenuController;
use Controllers\AppController;
use Controllers\LoginController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

// $router->get('/', [MenuController::class,'index']);

//!Rutas Para el Login
$router->get('/', [LoginController::class,'index']);
$router->post('/API/login', [LoginController::class,'login']);
$router->get('/logout', [LoginController::class,'logout']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
