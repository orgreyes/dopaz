<?php

namespace Controllers;

use Exception;
use Model\AsigMision;
use MVC\Router;

class AsigMisionController {
    public static function index(Router $router){

        $asigmisiones = AsigMision::all();

        $router->render('asigmisiones/index', []);
    }
    

 //!Funcion Buscar
 public static function buscarAPI()
 {
    // $cont_nombre = $_GET['cont_nombre'] ?? '';

    $sql = "SELECT DISTINCT cont_id, cont_nombre
    FROM contingentes 
    WHERE cont_situacion = 1";
    

     try {
         $asigmisiones = AsigMision::fetchArray($sql);

         echo json_encode($asigmisiones);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error',
             'codigo' => 0
         ]);
     }
 }


 public static function buscarMisionesContingenteAPI()
 {
     $contingenteId = $_GET['cont_id'];
     
     try {
         if ($contingenteId === null) {
             echo json_encode([
                 'mensaje' => 'Falta el ID del contingente',
                 'codigo' => 0
             ]);
             return;
         }
 
         // Consulta SQL para obtener las misiones de un contingente específico.
         $sql = "SELECT mc.mis_id, mc.mis_nombre
                 FROM cont_asig_misiones cam
                 JOIN cont_misiones_contingente mc ON cam.asig_mision = mc.mis_id
                 WHERE cam.asig_contingente = $contingenteId";
 
         // Ejecutar la consulta y obtener las misiones del contingente.
         $asigmisiones = AsigMision::fetchArray($sql);
 
         echo json_encode($asigmisiones);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error al obtener las misiones del contingente',
             'codigo' => 0
         ]);
     }
 }
 
 

//!Funcion Guardar
public static function guardarAPI()
{
    try {
        $contingenteData = $_POST;

        $contingente = new Contingente($contingenteData);
        $resultado = $contingente->crear();

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
         $cont_id = $_POST['cont_id'];
         $contingente = Contingente::find($cont_id);
         $contingente->cont_situacion = 0;
         $resultado = $contingente->actualizar();

         if($resultado['resultado'] == 1){
             echo json_encode([
                 'mensaje' => 'Datos del Contingente Eliminada correctamente',
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
         $contingenteData = $_POST;
 
         // Validar campos vacíos
        //  foreach ($contingenteData as $campo => $valor) {
        //      if (empty($valor)) {
        //          echo json_encode([
        //              'mensaje' => 'Llene Todos Los Campos',
        //              'codigo' => 0
        //          ]);
        //          return;
        //      }
        //  }

         $contingenteData['cont_situacion'] = 1;
 
         $contingente = new Contingente($contingenteData);
         $resultado = $contingente->actualizar();
 
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
