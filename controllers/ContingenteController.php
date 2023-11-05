<?php

namespace Controllers;

use Exception;
use Model\Contingente;
use MVC\Router;

class ContingenteController {
    public static function index(Router $router){

        $contingentes = Contingente::all();

        $router->render('contingentes/index', []);
    }
    

 //!Funcion Buscar
 public static function buscarAPI()
 {
    $cont_nombre = $_GET['cont_nombre'] ?? '';

    $sql = "SELECT * FROM contingentes WHERE cont_situacion = 1 ";
    
     $sql = "SELECT cont_id, cont_nombre, 
                cont_fecha_pre, cont_fecha_inicio, 
                cont_fecha_final, cont_fecha_post
            FROM contingentes
            WHERE cont_situacion = 1";

     try {

         $contingentes = Contingente::fetchArray($sql);

         echo json_encode($contingentes);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error',
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
