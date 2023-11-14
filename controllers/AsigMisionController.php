<?php

namespace Controllers;

use Exception;
use Model\AsigMision;
use Model\Contingente;
use Model\Mision;
use MVC\Router;

class AsigMisionController {
    public static function index(Router $router){
        $contingentes = static::buscaContingentes();
        $misiones = static::buscarMisiones();
        $asigmisiones = AsigMision::all();

        $router->render('asigmisiones/index', [
            'contingentes' => $contingentes,
            'misiones' => $misiones,
        ]);
    }
    
//!Funcion Select Contingentes
public static function buscaContingentes()
{
    $sql = "SELECT * FROM contingentes where cont_situacion = 1";

    try {
        $contingentes = Contingente::fetchArray($sql);
        return $contingentes;
    } catch (Exception $e) {
        return [];
    }
}
//!Funcion Select Misiones
public static function buscarMisiones()
{
    $sql = "SELECT * FROM cont_misiones_contingente where mis_situacion = 1";

    try {
        $misiones = Mision::fetchArray($sql);
        return $misiones;
    } catch (Exception $e) {
        return [];
    }
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
         $sql = "SELECT cam.asig_id, mc.mis_id, mc.mis_nombre
         FROM cont_asig_misiones cam
         JOIN cont_misiones_contingente mc ON cam.asig_mision = mc.mis_id
         WHERE cam.asig_contingente = $contingenteId AND cam.asig_situacion = 1 ";
 
         // Ejecutar la consulta y obtener las misiones del contingente.
         $asigmisiones = AsigMision::fetchArray($sql);
 
         echo json_encode($asigmisiones);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error al obtener las Grados de este puesto',
             'codigo' => 0
         ]);
     }
 }
 
 

//!Funcion Guardar
public static function guardarAPI()
{
    try {
        $asigMisionData = $_POST;

        $asigMision = new AsigMision($asigMisionData);
        $resultado = $asigMision->crear();

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
         $asig_id = $_POST['asig_id'];
         $asigMision = AsigMision::find($asig_id);
         $asigMision->asig_situacion = 0;
         $resultado = $asigMision->actualizar();

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
