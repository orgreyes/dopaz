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
                    cont_aspirantes.asp_id,
                    asp_nom1 || ' ' || asp_nom2 || ' ' || asp_ape1 || ' ' || asp_ape2 AS Nombre_Aspirante,
                    cont_puestos.pue_id
                FROM 
                    cont_aspirantes
                JOIN
                    cont_ingresos ON cont_aspirantes.asp_id = cont_ingresos.ing_aspirante
                JOIN
                    cont_puestos ON cont_ingresos.ing_puesto = cont_puestos.pue_id
                LEFT JOIN
                    cont_resultados ON cont_ingresos.ing_id = cont_resultados.res_aspirante
                WHERE   
                    YEAR(cont_ingresos.ing_fecha_cont) = YEAR(CURRENT)
                ORDER BY 
                    Nombre_Aspirante ASC;
";
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
     $asp_id = $_GET['asp_id'];
     $id_puesto = $_GET['pue_id'];
     
     try {
         if ($asp_id === null) {
             echo json_encode([
                 'mensaje' => 'Falta el ID del contingente',
                 'codigo' => 0
             ]);
             return;
         }
 
         $sql = "SELECT 
                        cont_evaluaciones.eva_id,
                        cont_evaluaciones.eva_nombre,
                        cont_resultados.res_id,
                        cont_resultados.res_nota,
                        cont_ingresos.ing_id
                    FROM 
                        cont_evaluaciones
                    JOIN 
                        cont_asig_evaluaciones ON cont_evaluaciones.eva_id = cont_asig_evaluaciones.asig_eva_nombre
                    JOIN 
                        cont_puestos ON cont_asig_evaluaciones.asig_eva_puesto = cont_puestos.pue_id
                    JOIN 
                        cont_ingresos ON cont_puestos.pue_id = cont_ingresos.ing_puesto
                    LEFT JOIN
                        cont_resultados ON cont_evaluaciones.eva_id = cont_resultados.res_evaluacion
                                        AND cont_ingresos.ing_id = cont_resultados.res_aspirante
                    WHERE 
                        cont_asig_evaluaciones.asig_eva_situacion = 1 
                        AND cont_puestos.pue_id = $id_puesto
                        AND cont_ingresos.ing_id = $asp_id
                    ORDER BY 
                        cont_evaluaciones.eva_nombre ASC;
     ";
 
         // Ejecutar la consulta y obtener las misiones del contingente.
         $asig_evaluacion = Resultado::fetchArray($sql);
 
         echo json_encode($asig_evaluacion);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error al obtener lo Resultados de este puesto',
             'codigo' => 0
         ]);
     }
 }
 
 

//!Funcion Guardar
public static function guardarAPI() {
    try {
        $Id_Ingreso = $_GET['ing_id'];
        $Id_Evaluacion = $_GET['eva_id'];
        $Nota = $_GET['res_nota'];

        
        
        // ! Aca se recibe los datos que se guardaran en otra tabla.
        $datos['res_aspirante'] = $Id_Ingreso;
        $datos['res_evaluacion'] = $Id_Evaluacion;
        $datos['res_nota'] = $Nota;
        
        $Notas_Calificadas = new Resultado($datos);
        $result = $Notas_Calificadas->guardar();

        // ! Solo envía una respuesta JSON al final
        if ($result['resultado'] == 1) {
            echo json_encode([
                'mensaje' => 'Requisito Provado',
                'codigo' => 1
            ]);
        } else {
            echo json_encode([
                'mensaje' => 'Ocurrió un error',
                'codigo' => 0
            ]);
        }
    } catch (Exception $e) {
        // ! Si hay una excepción, envía una respuesta JSON de error
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'El Requisito ya fue Aprovado',
            'codigo' => 2
        ]);
    }
}

//!Funcion Modificar

public static function modificarAPI() {
    try {
        $notaData = $_POST;


        $notaData['res_situacion'] = 1;

        $nota = new Resultado($notaData);
        $resultado = $nota->actualizar();

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
