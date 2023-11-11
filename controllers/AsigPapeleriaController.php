<?php

namespace Controllers;

use Exception;
use Model\AsigPapeleria;
use Model\Puesto;
use Model\Papeleria;
use MVC\Router;

class AsigPapeleriaController {
    public static function index(Router $router){
        $puestos = static::buscarPuestos();
        $papelerias = static::buscarPapeleria();
        $asigpapelerias = AsigPapeleria::all();

        $router->render('asigpapelerias/index', [
            'puestos' => $puestos,
            'papelerias' => $papelerias,
        ]);
    }
    
//!Funcion Select Puestos
public static function buscarPuestos()
{
    $sql = "SELECT * FROM cont_puestos where pue_situacion = 1";

    try {
        $puestos = Puesto::fetchArray($sql);
        return $puestos;
    } catch (Exception $e) {
        return [];
    }
}
//!Funcion Select Papelria
public static function buscarPapeleria()
{
    $sql = "SELECT * FROM cont_papeleria where pap_situacion = 1";

    try {
        $papelerias = Papeleria::fetchArray($sql);
        return $papelerias;
    } catch (Exception $e) {
        return [];
    }
}

 //!Funcion Buscar
 public static function buscarAPI()
 {
    // $cont_nombre = $_GET['cont_nombre'] ?? '';

    $sql = "SELECT DISTINCT pue_id, pue_nombre
    FROM cont_puestos 
    WHERE pue_situacion = 1";
    

     try {
         $asigmisiones = Puesto::fetchArray($sql);

         echo json_encode($asigmisiones);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error',
             'codigo' => 0
         ]);
     }
 }


 public static function buscarPapeleriaPuestoAPI()
 {
     $puestoId = $_GET['pue_id'];
     
     try {
         if ($puestoId === null) {
             echo json_encode([
                 'mensaje' => 'Falta el ID del Puesto',
                 'codigo' => 0
             ]);
             return;
         }
 
         $sql = "SELECT 
                        pue.pue_id AS puesto_id,
                        pue.pue_nombre AS puesto_nombre,
                        pap.pap_id AS papeleria_id,
                        pap.pap_nombre AS papeleria_nombre
                    FROM 
                        cont_papeleria pap
                    JOIN 
                        cont_asig_papeleria asig_pap ON pap.pap_id = asig_pap.asig_pap_papeleria
                    JOIN 
                        cont_puestos pue ON asig_pap.asig_pap_puesto = pue.pue_id
                    WHERE 
                        pue.pue_id = $puestoId";
 
         // Ejecutar la consulta y obtener las misiones del contingente.
         $asigpapeleria = AsigPapeleria::fetchArray($sql);
 
         echo json_encode($asigpapeleria);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error al obtener las papeleria del puesto',
             'codigo' => 0
         ]);
     }
 }
 
 

//!Funcion Guardar
public static function guardarAPI()
{
    try {
        $asigMisionData = $_POST;

        $asigMision = new AsigPapeleria($asigMisionData);
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
         $asigMision = AsigPapeleria::find($asig_id);
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
 
         $contingente = new AsigPapeleria($contingenteData);
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
