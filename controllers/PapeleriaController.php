<?php

namespace Controllers;

use Exception;
use Model\Papeleria;
use MVC\Router;

class PapeleriaController {
    public static function index(Router $router){

        $papeleria = Papeleria::all();

        $router->render('papeleria/index', []);
    }
    

 //!Funcion Buscar
 public static function buscarAPI()
 {
    $sql = "SELECT * FROM cont_papeleria WHERE pap_situacion = 1 ";
    
     $sql = "SELECT pap_id, pap_nombre
     FROM cont_papeleria
     WHERE pap_situacion = 1;";

     try {

         $papelerias = Papeleria::fetchArray($sql);

         echo json_encode($papelerias);
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
        $papelerias = new Papeleria($_POST);
        $resultado = $papelerias->crear();

        if ($resultado['resultado'] == 1) {
            echo json_encode([
                'mensaje' => 'Documento guardado correctamente',
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
         $pap_id = $_POST['pap_id'];
         $papeleria = Papeleria::find($pap_id);
         $papeleria->pap_situacion = 0;
         $resultado = $papeleria->actualizar();

         if($resultado['resultado'] == 1){
             echo json_encode([
                 'mensaje' => 'Papeleria Eliminada correctamente',
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
         $papeleriaData = $_POST;
 
         // Validar campos vacíos
         foreach ($papeleriaData as $campo => $valor) {
             if (empty($valor)) {
                 echo json_encode([
                     'mensaje' => 'Llene Todos Los Campos',
                     'codigo' => 0
                 ]);
                 return;
             }
         }

         $papeleriaData['pap_situacion'] = 1;
 
         $papeleria = new Papeleria($papeleriaData);
         $resultado = $papeleria->actualizar();
 
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
