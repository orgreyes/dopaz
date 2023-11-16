<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;

use Controllers\AppController;
use Controllers\EvaluacionController;
use Controllers\PuestoController;
use Controllers\AsigGradoController;
use Controllers\MisionController;
use Controllers\UsuarioController;
use Controllers\AspiranteController;
use Controllers\ContingenteController;
use Controllers\AsigMisionController;
use Controllers\IngresoController;
use Controllers\ResultadoController;
use Controllers\RequisitoController;
use Controllers\AsigRequisitoController;
use Controllers\AsigEvaluacionController;


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

//!Rutas para Requisitos
$router->get('/requisitos', [RequisitoController::class,'index']);
$router->get('/API/requisitos/buscar', [RequisitoController::class,'buscarAPI']);
$router->post('/API/requisitos/eliminar', [RequisitoController::class,'eliminarAPI']);
$router->post('/API/requisitos/modificar', [RequisitoController::class,'modificarAPI']);
$router->post('/API/requisitos/guardar', [RequisitoController::class,'guardarAPI']);

//!Rutas para Puestos
$router->get('/puestos', [PuestoController::class,'index']);
$router->get('/API/puestos/buscar', [PuestoController::class,'buscarAPI']);
$router->post('/API/puestos/eliminar', [PuestoController::class,'eliminarAPI']);
$router->post('/API/puestos/modificar', [PuestoController::class,'modificarAPI']);
$router->post('/API/puestos/guardar', [PuestoController::class,'guardarAPI']);


//!Rutas para Asignar Grados A los Puestos
$router->get('/asiggrados', [AsigGradoController::class,'index']);
$router->get('/API/asiggrados/buscar', [AsigGradoController::class,'buscarAPI']);
$router->get('/API/asiggrados/buscarGradosPuestos', [AsigGradoController::class,'buscarGradosPuestosAPI']);
$router->post('/API/asiggrados/eliminar', [AsigGradoController::class,'eliminarAPI']);
$router->post('/API/asiggrados/modificar', [AsigGradoController::class,'modificarAPI']);
$router->post('/API/asiggrados/guardar', [AsigGradoController::class,'guardarAPI']);

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

//!Rutas para Asignar Requisitos a cada Puestos
$router->get('/asigrequisitos', [AsigRequisitoController::class,'index']);
$router->get('/API/asigrequisitos/buscar', [AsigRequisitoController::class,'buscarAPI']);
$router->get('/API/asigrequisitos/buscarRequisitoPuesto', [AsigRequisitoController::class,'buscarRequisitoPuestoAPI']);
$router->post('/API/asigrequisitos/eliminar', [AsigRequisitoController::class,'eliminarAPI']);
$router->post('/API/asigrequisitos/modificar', [AsigRequisitoController::class,'modificarAPI']);
$router->post('/API/asigrequisitos/guardar', [AsigRequisitoController::class,'guardarAPI']);

//!Rutas para Asignar Evaluaciones a cada Puesto
$router->get('/asigevaluaciones', [AsigEvaluacionController::class,'index']);
$router->get('/API/asigevaluaciones/buscar', [AsigEvaluacionController::class,'buscarAPI']);
$router->get('/API/asigevaluaciones/buscarEvaluacionPuesto', [AsigEvaluacionController::class,'buscarEvaluacionPuestoAPI']);
$router->post('/API/asigevaluaciones/eliminar', [AsigEvaluacionController::class,'eliminarAPI']);
$router->post('/API/asigevaluaciones/modificar', [AsigEvaluacionController::class,'modificarAPI']);
$router->post('/API/asigevaluaciones/guardar', [AsigEvaluacionController::class,'guardarAPI']);

//!Rutas para Primer Ingreso del Personal
$router->get('/usuarios', [UsuarioController::class,'index']);
$router->get('/API/usuarios/buscar', [UsuarioController::class,'buscarAPI']);
$router->get('/API/usuarios/buscarPuesto', [UsuarioController::class,'buscarPuesto']);
$router->post('/API/usuarios/guardar', [UsuarioController::class,'guardarAPI']);
$router->post('/API/usuarios/enviar', [UsuarioController::class,'enviarAPI']);

//!Rutas para Personal de que busca optar por una plaza
$router->get('/ingresos', [IngresoController::class,'index']);
$router->get('/API/ingresos/buscar', [IngresoController::class,'buscarAPI']);
$router->get('/API/ingresos/buscarRequisitoPuesto', [IngresoController::class,'buscarRequisitoPuestoAPI']);
$router->get('/API/ingresos/guardar', [IngresoController::class,'guardarAPI']);
$router->post('/API/ingresos/desaprovar', [IngresoController::class,'desaprovarAPI']);
$router->post('/API/ingresos/aprovar', [IngresoController::class,'aprovarAPI']);


//!Rutas para Asignar Resultados
$router->get('/resultados', [ResultadoController::class,'index']);
$router->get('/API/resultados/buscar', [ResultadoController::class,'buscarAPI']);
$router->get('/API/resultados/buscarEvaluaciones', [ResultadoController::class,'buscarEvaluacionesAPI']);
$router->post('/API/resultados/eliminar', [ResultadoController::class,'eliminarAPI']);
$router->post('/API/resultados/modificar', [ResultadoController::class,'modificarAPI']);
$router->get('/API/resultados/guardar', [ResultadoController::class,'guardarAPI']);


// //!Rutas para Primer Ingreso del Personal
// $router->get('/aspirantes', [AspiranteController::class,'index']);
// $router->get('/API/aspirantes/buscar', [AspiranteController::class,'buscarAPI']);
// $router->post('/API/aspirantes/eliminar', [AspiranteController::class,'eliminarAPI']);
// $router->post('/API/aspirantes/modificar', [AspiranteController::class,'modificarAPI']);
// $router->post('/API/aspirantes/guardar', [AspiranteController::class,'guardarAPI']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
