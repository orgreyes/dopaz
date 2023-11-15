<?php

namespace Controllers;

use Exception;
use Model\AsigEvaluacion;
use Model\Puesto;
use Model\Evaluacion;
use MVC\Router;

class AsigEvaluacionController {
    public static function index(Router $router){
        $puestos = static::buscarPuestos();
        $evaluaciones = static::buscarEvaluaciones();
        $asigevaluaciones = AsigEvaluacion::all();

        $router->render('asigevaluaciones/index', [
            'puestos' => $puestos,
            'evaluaciones' => $evaluaciones,
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
//!Funcion Select requisitoelria
public static function buscarEvaluaciones()
{
    $sql = "SELECT * FROM cont_evaluaciones where eva_situacion = 1";

    try {
        $evaluaciones = AsigEvaluacion::fetchArray($sql);
        return $evaluaciones;
    } catch (Exception $e) {
        return [];
    }
}
//?-----------------------------------------------------------------------------------------------------

//!Funcion Guardar
public static function guardarAPI()
{
    try {
        $asigevaluacionData = $_POST;

        $asigEvaluacion = new AsigEvaluacion($asigevaluacionData);
        $resultado = $asigEvaluacion->crear();

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

 //!Funcion buscarEvaluacionPuestoAPI

 public static function buscarEvaluacionPuestoAPI()
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
                    eva.eva_nombre,
                    ase.asig_eva_id,
                    ase.asig_eva_situacion
                    FROM
                        cont_puestos pue
                    JOIN
                        cont_asig_evaluaciones ase ON pue.pue_id = ase.asig_eva_puesto
                    JOIN
                        cont_evaluaciones eva ON ase.asig_eva_nombre = eva.eva_id
                    WHERE
                        pue.pue_id = $puestoId
                        AND ase.asig_eva_situacion = 1";
 
         // Ejecutar la consulta y obtener las misiones del contingente.
         $asig_evaluaciones = AsigEvaluacion::fetchArray($sql);
 
         echo json_encode($asig_evaluaciones);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error al obtener los Requisitos del puesto',
             'codigo' => 0
         ]);
     }
 }


 

 //!Funcion Eliminar
 public static function eliminarAPI(){
     try{
         $asig_eva_id = $_POST['asig_eva_id'];
         $asigEvaluacion = AsigEvaluacion::find($asig_eva_id);
         $asigEvaluacion->asig_eva_situacion = 0;
         $resultado = $asigEvaluacion->actualizar();

         if($resultado['resultado'] == 1){
             echo json_encode([
                 'mensaje' => 'Requisito Removido correctamente',
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
}
