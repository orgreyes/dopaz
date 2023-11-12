<?php

namespace Controllers;

use Exception;
use Model\Requisito;
use MVC\Router;

class RequisitoController {
    public static function index(Router $router){

        $requisitos = Requisito::all();

        $router->render('requisitos/index', []);
    }
    

 //!Funcion Buscar
 public static function buscarAPI()
 {
    $req_nombre = $_GET['req_nombre'] ?? '';

    if ($req_nombre != '') {           
        $sql .= " AND lower(req_nombre) LIKE '%$req_nombre%' ";
    }

    $sql = "SELECT * FROM cont_requisitos WHERE req_situacion = 1 ";
    
     $sql = "SELECT req_id, req_nombre
     FROM cont_requisitos
     WHERE req_situacion = 1;";

     try {

         $requisitos = Requisito::fetchArray($sql);

         echo json_encode($requisitos);
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
        $requisitos = new Requisito($_POST);
        $resultado = $requisitos->crear();

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
         $req_id = $_POST['req_id'];
         $requisito = Requisito::find($req_id);
         $requisito->req_situacion = 0;
         $resultado = $requisito->actualizar();

         if($resultado['resultado'] == 1){
             echo json_encode([
                 'mensaje' => 'Requisito Eliminada correctamente',
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
         $requisitoData = $_POST;
 
         // Validar campos vacíos
         foreach ($requisitoData as $campo => $valor) {
             if (empty($valor)) {
                 echo json_encode([
                     'mensaje' => 'Llene Todos Los Campos',
                     'codigo' => 0
                 ]);
                 return;
             }
         }

         $requisitoData['req_situacion'] = 1;
 
         $requisito = new Requisito($requisitoData);
         $resultado = $requisito->actualizar();
 
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
