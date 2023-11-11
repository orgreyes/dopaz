<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;

use Controllers\AppController;
use Controllers\EvaluacionController;
use Controllers\PuestoController;
use Controllers\MisionController;
use Controllers\UsuarioController;
use Controllers\AspiranteController;
use Controllers\ContingenteController;
use Controllers\AsigMisionController;
use Controllers\IngresoController;
use Controllers\ResultadoController;
use Controllers\PapeleriaController;
use Controllers\AsigPapeleriaController;


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

//!Rutas para Papeleria
$router->get('/papelerias', [PapeleriaController::class,'index']);
$router->get('/API/papelerias/buscar', [PapeleriaController::class,'buscarAPI']);
$router->post('/API/papelerias/eliminar', [PapeleriaController::class,'eliminarAPI']);
$router->post('/API/papelerias/modificar', [PapeleriaController::class,'modificarAPI']);
$router->post('/API/papelerias/guardar', [PapeleriaController::class,'guardarAPI']);

//!Rutas para Puestos
$router->get('/puestos', [PuestoController::class,'index']);
$router->get('/API/puestos/buscar', [PuestoController::class,'buscarAPI']);
$router->post('/API/puestos/eliminar', [PuestoController::class,'eliminarAPI']);
$router->post('/API/puestos/modificar', [PuestoController::class,'modificarAPI']);
$router->post('/API/puestos/guardar', [PuestoController::class,'guardarAPI']);


//!Rutas para Contingentes
$router->get('/contingentes', [ContingenteController::class,'index']);
$router->get('/API/contingentes/buscar', [ContingenteController::class,'buscarAPI']);
$router->post('/API/contingentes/eliminar', [ContingenteController::class,'eliminarAPI']);
$router->post('/API/contingentes/modificar', [ContingenteController::class,'modificarAPI']);
$router->post('/API/contingentes/guardar', [ContingenteController::class,'guardarAPI']);

//!Rutas para Misiones
$router->get('/misiones', [MisionController::class,'index']);
$router->get('/API/misiones/buscar', [MisionController::class,'buscarAPI']);
$router->get('/API/misiones/buscarMapa', [MisionController::class,'buscarMapaAPI']);
$router->post('/API/misiones/eliminar', [MisionController::class,'eliminarAPI']);
$router->post('/API/misiones/modificar', [MisionController::class,'modificarAPI']);
$router->post('/API/misiones/guardar', [MisionController::class,'guardarAPI']);

//!Rutas para Asignar Misiones a Contingentes
$router->get('/asigmisiones', [AsigMisionController::class,'index']);
$router->get('/API/asigmisiones/buscar', [AsigMisionController::class,'buscarAPI']);
$router->get('/API/asigmisiones/buscarMisionesContingente', [AsigMisionController::class,'buscarMisionesContingenteAPI']);
$router->post('/API/asigmisiones/eliminar', [AsigMisionController::class,'eliminarAPI']);
$router->post('/API/asigmisiones/modificar', [AsigMisionController::class,'modificarAPI']);
$router->post('/API/asigmisiones/guardar', [AsigMisionController::class,'guardarAPI']);

//!Rutas para Asignar Papereria requerida a Puestos
$router->get('/asigpapelerias', [AsigPapeleriaController::class,'index']);
$router->get('/API/asigpapelerias/buscar', [AsigPapeleriaController::class,'buscarAPI']);
$router->get('/API/asigpapelerias/buscarPapeleriaPuesto', [AsigPapeleriaController::class,'buscarPapeleriaPuestoAPI']);
$router->post('/API/asigpapelerias/eliminar', [AsigPapeleriaController::class,'eliminarAPI']);
$router->post('/API/asigpapelerias/modificar', [AsigPapeleriaController::class,'modificarAPI']);
$router->post('/API/asigpapelerias/guardar', [AsigPapeleriaController::class,'guardarAPI']);

//!Rutas para Primer Ingreso del Personal
$router->get('/usuarios', [UsuarioController::class,'index']);
$router->get('/API/usuarios/buscar', [UsuarioController::class,'buscarAPI']);
$router->get('/API/usuarios/buscarPuesto', [UsuarioController::class,'buscarPuesto']);
$router->post('/API/usuarios/guardar', [UsuarioController::class,'guardarAPI']);
$router->post('/API/usuarios/enviar', [UsuarioController::class,'enviarAPI']);

//!Rutas para Personal de que busca optar por una plaza
$router->get('/ingresos', [IngresoController::class,'index']);
$router->get('/API/ingresos/buscar', [IngresoController::class,'buscarAPI']);
$router->post('/API/ingresos/eliminar', [IngresoController::class,'eliminarAPI']);
$router->post('/API/ingresos/modificar', [IngresoController::class,'modificarAPI']);
$router->post('/API/ingresos/guardar', [IngresoController::class,'guardarAPI']);


//!Rutas para Asignar Resultados
$router->get('/resultados', [ResultadoController::class,'index']);
$router->get('/API/resultados/buscar', [ResultadoController::class,'buscarAPI']);
$router->get('/API/resultados/buscarEvaluaciones', [ResultadoController::class,'buscarEvaluacionesAPI']);
$router->post('/API/resultados/eliminar', [ResultadoController::class,'eliminarAPI']);
$router->post('/API/resultados/modificar', [ResultadoController::class,'modificarAPI']);
$router->post('/API/resultados/guardar', [ResultadoController::class,'guardarAPI']);

// //!Rutas para Primer Ingreso del Personal
// $router->get('/aspirantes', [AspiranteController::class,'index']);
// $router->get('/API/aspirantes/buscar', [AspiranteController::class,'buscarAPI']);
// $router->post('/API/aspirantes/eliminar', [AspiranteController::class,'eliminarAPI']);
// $router->post('/API/aspirantes/modificar', [AspiranteController::class,'modificarAPI']);
// $router->post('/API/aspirantes/guardar', [AspiranteController::class,'guardarAPI']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
