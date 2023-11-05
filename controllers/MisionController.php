<?php

namespace Controllers;

use Exception;
use Model\Mision;
use MVC\Router;

class MisionController {
    public static function index(Router $router){

        $misiones = Mision::all();

        $router->render('misiones/index', []);
    }
    
 //!Funcion Buscar en el Mapa
    public static function buscarMapaAPI(){
        $mis_nombre = $_GET['mis_nombre'] ?? '';
        $sql = "SELECT * FROM cont_misiones_contingente WHERE mis_situacion = '1'";
    
    
        try {
            $cont_misiones_contingente = Mision::fetchArray($sql); 
            echo json_encode($cont_misiones_contingente);
        } catch (Exception $e) {
            echo json_encode([
                'detalle' => $e->getMessage(),
                'mensaje' => 'Ocurrió un error',
                'codigo' => 0
            ]);
        }
    }

 //!Funcion Buscar
 public static function buscarAPI()
 {
    $mis_nombre = $_GET['mis_nombre'] ?? '';

    $sql = "SELECT * FROM cont_misiones_contingente WHERE mis_situacion = 1 ";
    
     $sql = "SELECT mis_id, mis_nombre, 
                mis_latitud, mis_longitud
            FROM cont_misiones_contingente
            WHERE mis_situacion = 1";

     try {

         $misiones = Mision::fetchArray($sql);

         echo json_encode($misiones);
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
        $misionData = $_POST;

        $mision = new Mision($misionData);
        $resultado = $mision->crear();

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
         $mis_id = $_POST['mis_id'];
         $mision = Mision::find($mis_id);
         $mision->mis_situacion = 0;
         $resultado = $mision->actualizar();

         if($resultado['resultado'] == 1){
             echo json_encode([
                 'mensaje' => 'Datos de la Mision Eliminada correctamente',
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
         $misionData = $_POST;
 
         // Validar campos vacíos
         foreach ($misionData as $campo => $valor) {
             if (empty($valor)) {
                 echo json_encode([
                     'mensaje' => 'Llene Todos Los Campos',
                     'codigo' => 0
                 ]);
                 return;
             }
         }

         $misionData['mis_situacion'] = 1;
 
         $mision = new Mision($misionData);
         $resultado = $mision->actualizar();
 
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
