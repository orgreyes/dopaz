<?php

namespace Controllers;

use Exception;
use Model\Evaluacion;
use MVC\Router;

class EvaluacionController {
    public static function index(Router $router){

        $evaluaciones = Evaluacion::all();

        $router->render('evaluaciones/index', []);
    }
    

 //!Funcion Buscar
 public static function buscarAPI()
 {
    $eva_nombre = $_GET['eva_nombre'] ?? '';

    if ($eva_nombre != '') {           
        $sql .= " AND lower(eva_nombre) LIKE '%$eva_nombre%' ";
    }

    $sql = "SELECT * FROM cont_evaluaciones WHERE eva_situacion = 1 ";
    
     $sql = "SELECT eva_id, eva_nombre
     FROM cont_evaluaciones
     WHERE eva_situacion = 1;";

     try {

         $evaluaciones = Evaluacion::fetchArray($sql);

         echo json_encode($evaluaciones);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error',
             'codigo' => 0
         ]);
     }
 }

  //!Funcion Guardar
 public static function guardarAPI(){
     
    try {
        $evaluacion = new Evaluacion($_POST);
        $resultado = $evaluacion->crear();

        if ($resultado['resultado'] == 1) {
            echo json_encode([
                'mensaje' => 'Registro guardado correctamente',
                'codigo' => 1
            ]);
        } else {
            echo json_encode([
                'mensaje' => 'Ocurrió un error',
                'codigo' => 0
            ]);
        }
        // echo json_encode($resultado);
    } catch (Exception $e) {
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'Ocurrió un error',
            'codigo' => 0
        ]);
    }
}

 

 //!Funcion Eliminar
 public static function eliminarAPI(){
     try{
         $eva_id = $_POST['eva_id'];
         $evaluacion = Evaluacion::find($eva_id);
         $evaluacion->eva_situacion = 0;
         $resultado = $evaluacion->actualizar();

         if($resultado['resultado'] == 1){
             echo json_encode([
                 'mensaje' => 'Evaluacion Eliminada correctamente',
                 'codigo' => 1
             ]);
         }else{
             echo json_encode([
                 'mensaje' => 'Ocurrio un error',
                 'codigo' => 0
             ]);
         }
     }catch(Exception $e){
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje'=> 'Ocurrio un Error',
             'codigo' => 0
     ]);
     }
 }


 //!Funcion Modificar

 public static function modificarAPI() {
     try {
         $evaluacionData = $_POST;
 
         // Validar campos vacíos
         foreach ($evaluacionData as $campo => $valor) {
             if (empty($valor)) {
                 echo json_encode([
                     'mensaje' => 'Llene Todos Los Campos',
                     'codigo' => 0
                 ]);
                 return;
             }
         }

         $evaluacionData['eva_situacion'] = 1;
 
         $evaluacion = new Evaluacion($evaluacionData);
         $resultado = $evaluacion->actualizar();
 
         if ($resultado['resultado'] == 1) {
             echo json_encode([
                 'mensaje' => 'Actualización de Datos Correcta',
                 'codigo' => 1
             ]);
         } else {
             echo json_encode([
                 'mensaje' => 'Ocurrió un error',
                 'codigo' => 0
             ]);
         }
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un Error',
             'codigo' => 0
         ]);
     }
 }
}
