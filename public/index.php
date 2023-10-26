<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;

use Controllers\AppController;
use Controllers\EvaluacionController;
use Controllers\PuestoController;
use Controllers\DestinoController;
use Controllers\UsuarioController;


$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);


//!MANTENIMIENTOS BASICOS.

//!Rutas para Evaluaciones
$router->get('/evaluaciones', [EvaluacionController::class,'index']);
$router->get('/API/evaluaciones/buscar', [EvaluacionController::class,'buscarAPI']);
$router->post('/API/evaluaciones/eliminar', [EvaluacionController::class,'eliminarAPI']);
$router->post('/API/evaluaciones/modificar', [EvaluacionController::class,'modificarAPI']);
$router->post('/API/evaluaciones/guardar', [EvaluacionController::class,'guardarAPI']);


//!Rutas para Puestos
$router->get('/puestos', [PuestoController::class,'index']);
$router->get('/API/puestos/buscar', [PuestoController::class,'buscarAPI']);
$router->post('/API/puestos/eliminar', [PuestoController::class,'eliminarAPI']);
$router->post('/API/puestos/modificar', [PuestoController::class,'modificarAPI']);
$router->post('/API/puestos/guardar', [PuestoController::class,'guardarAPI']);


//!Rutas para Destinos
$router->get('/destinos', [DestinoController::class,'index']);
$router->get('/API/destinos/buscar', [DestinoController::class,'buscarAPI']);
$router->post('/API/destinos/eliminar', [DestinoController::class,'eliminarAPI']);
$router->post('/API/destinos/modificar', [DestinoController::class,'modificarAPI']);
$router->post('/API/destinos/guardar', [DestinoController::class,'guardarAPI']);


//!Rutas para Aspirantes
$router->get('/usuarios', [UsuarioController::class,'index']);
$router->get('/API/usuarios/buscar', [UsuarioController::class,'buscarAPI']);
$router->post('/API/usuarios/eliminar', [UsuarioController::class,'eliminarAPI']);
$router->post('/API/usuarios/modificar', [UsuarioController::class,'modificarAPI']);
$router->post('/API/usuarios/guardar', [UsuarioController::class,'guardarAPI']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
