<?php

namespace Controllers;

use Exception;
use Model\Resultado;
use Model\Evaluacion;
use MVC\Router;

class ResultadoController {
    public static function index(Router $router){
        $evaluaciones = static::buscarEvaluaciones();
        $resultados = Resultado::all();

        $router->render('resultados/index', [
            'evaluaciones' => $evaluaciones,
        ]);
    }
    
// //!Funcion Select Evaluaciones
public static function buscarEvaluaciones()
{
    $sql = "SELECT * FROM cont_evaluaciones where eva_situacion = 1";

    try {
        $evaluaciones = Evaluacion::fetchArray($sql);
        return $evaluaciones;
    } catch (Exception $e) {
        return [];
    }
}

 //!Funcion Buscar
 public static function buscarAPI()
 {
    $sql = "SELECT DISTINCT
                cont_aspirantes.asp_id AS ID_Aspirante,
                asp_nom1 || ' ' || asp_nom2 || ' ' || asp_ape1 || ' ' || asp_ape2 AS Nombre_Aspirante
            FROM 
                cont_aspirantes
            JOIN
                cont_ingresos ON cont_aspirantes.asp_id = cont_ingresos.ing_aspirante
            LEFT JOIN
                cont_resultados ON cont_ingresos.ing_id = cont_resultados.res_aspirante
            WHERE   YEAR(cont_ingresos.ing_fecha_cont) = YEAR(CURRENT)
            ORDER BY 
                Nombre_Aspirante ASC";
    

     try {
         $resultados = Resultado::fetchArray($sql);

         echo json_encode($resultados);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error',
             'codigo' => 0
         ]);
     }
 }


 public static function buscarEvaluacionesAPI()
 {
     $id_aspirante = $_GET['id_aspirante'];
     
     try {
         if ($id_aspirante === null) {
             echo json_encode([
                 'mensaje' => 'Falta el ID del contingente',
                 'codigo' => 0
             ]);
             return;
         }
 
         // Consulta SQL para obtener las misiones de un contingente específico.
         $sql = "SELECT 
         cont_aspirantes.asp_id AS ID_Aspirante,
         asp_nom1 || ' ' || asp_nom2 || ' ' || asp_ape1 || ' ' || asp_ape2 AS Nombre_Aspirante,
         cont_evaluaciones.eva_id AS ID_Evaluacion,
         eva_nombre AS Evaluacion_Asignada,
         res_nota AS Nota
     FROM 
         cont_aspirantes
     JOIN
         cont_ingresos ON cont_aspirantes.asp_id = cont_ingresos.ing_aspirante
     LEFT JOIN
         cont_resultados ON cont_ingresos.ing_id = cont_resultados.res_aspirante
     JOIN
         cont_evaluaciones ON cont_resultados.res_evaluacion = cont_evaluaciones.eva_id
     WHERE 
         cont_aspirantes.asp_id = $id_aspirante
         AND YEAR(cont_ingresos.ing_fecha_cont) = YEAR(CURRENT)
     ORDER BY 
         Nombre_Aspirante ASC; ";
 
         // Ejecutar la consulta y obtener las misiones del contingente.
         $asig_evaluacion = Resultado::fetchArray($sql);
 
         echo json_encode($asig_evaluacion);
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
    $id_aspirante = $_GET['id_aspirante'];
json_encode($id_aspirante);
return;
    try {
        $asigEvaluacionData = $_POST;

        $asigEvaluacion = new Resultado($asigEvaluacionData);
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
