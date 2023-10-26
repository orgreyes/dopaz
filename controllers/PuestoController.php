<?php

namespace Controllers;

use Exception;
use Model\Puesto;
use MVC\Router;

class PuestoController {
    public static function index(Router $router){

        $puesto = Puesto::all();

        $router->render('puestos/index', []);
    }
    

 //!Funcion Buscar
 public static function buscarAPI()
 {  
     $sql = "SELECT pue_id, pue_nombre
     FROM cont_puestos
     WHERE pue_situacion = 1;";

     try {

         $puestos = Puesto::fetchArray($sql);

         echo json_encode($puestos);
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
        $puesto = new Puesto($_POST);
        $resultado = $puesto->crear();

        if ($resultado['resultado'] == 1) {
            echo json_encode([
                'mensaje' => 'Puesto guardado correctamente',
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
         $pue_id = $_POST['pue_id'];
         $puesto = Puesto::find($pue_id);
         $puesto->pue_situacion = 0;
         $resultado = $puesto->actualizar();

         if($resultado['resultado'] == 1){
             echo json_encode([
                 'mensaje' => 'Puesto Eliminado Correctamente',
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
         $puestoData = $_POST;
 
         // Validar campos vacíos
         foreach ($puestoData as $campo => $valor) {
             if (empty($valor)) {
                 echo json_encode([
                     'mensaje' => 'Llene Todos Los Campos',
                     'codigo' => 0
                 ]);
                 return;
             }
         }

         $puestoData['pue_situacion'] = 1;
 
         $puesto = new Puesto($puestoData);
         $resultado = $puesto->actualizar();
 
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
