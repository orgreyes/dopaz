<?php

namespace Controllers;

use Exception;
use Model\Ingreso;
use Model\AsigRequisito;
use Model\RequisitoAprovado;
use MVC\Router;

class IngresoController {
    public static function index(Router $router){

        $ingresos = Ingreso::all();

        $router->render('ingresos/index', []);
    }
    

   //!Funcion Buscar Notas
   public static function buscarNotasAPI()
   {
       try {
           // Consulta para obtener la lista de evaluaciones asociadas a cada puesto
           $queryEvaluaciones = "SELECT
           p.pue_id,
           p.pue_nombre AS puesto_nombre,
           e.eva_id,
           e.eva_nombre
       FROM
           cont_puestos p
       LEFT JOIN
           cont_asig_evaluaciones ae ON p.pue_id = ae.asig_eva_puesto
       LEFT JOIN
           cont_evaluaciones e ON ae.asig_eva_nombre = e.eva_id
       WHERE
           p.pue_id = 2";
   
           $evaluaciones = Ingreso::fetchArray($queryEvaluaciones);
   
           // Crear un array para almacenar los nombres de evaluaciones
           $evaluacionesArray = [];
           foreach ($evaluaciones as $evaluacion) {
               $evaluacionesArray[$evaluacion['pue_id']][] = $evaluacion['eva_nombre'];
           }
   
           // Consulta para obtener las notas de las evaluaciones filtradas por el array
           $queryNotas = "SELECT
    c.cont_nombre AS Contingente,
    p.pue_nombre AS puesto_nombre,
    " . self::buildMaxCaseStatements($evaluacionesArray) . "
FROM
    cont_ingresos i
LEFT JOIN
    cont_puestos p ON i.ing_puesto = p.pue_id
LEFT JOIN
    contingentes c ON i.ing_contingente = c.cont_id
LEFT JOIN
    cont_asig_evaluaciones ae ON p.pue_id = ae.asig_eva_puesto
LEFT JOIN
    cont_evaluaciones e ON ae.asig_eva_nombre = e.eva_id
LEFT JOIN
    cont_resultados r ON i.ing_aspirante = r.res_aspirante AND e.eva_id = r.res_evaluacion
WHERE
    i.ing_puesto = 2
GROUP BY
    i.ing_aspirante, i.ing_puesto, c.cont_nombre, p.pue_nombre
ORDER BY
    " . self::buildOrderByStatements($evaluacionesArray);
                            


           $resultados = Ingreso::fetchArray($queryNotas);
           echo json_encode($resultados);
       } catch (Exception $e) {
           echo json_encode([
               'detalle' => $e->getMessage(),
               'mensaje' => 'Ocurrió un error',
               'codigo' => 0
           ]);
       }
   }
   // Función para construir las declaraciones MAX(CASE WHEN ...)
private static function buildMaxCaseStatements($evaluacionesArray)
{
    $maxCaseStatements = '';
    foreach ($evaluacionesArray as $puestoId => $evaluaciones) {
        foreach ($evaluaciones as $evaluacion) {
            $maxCaseStatements .= "MAX(CASE WHEN e.eva_nombre = '$evaluacion' THEN r.res_nota END) AS $evaluacion,\n";
        }
    }

    return rtrim($maxCaseStatements, ",\n");
}

// Función para construir la cláusula ORDER BY
private static function buildOrderByStatements($evaluacionesArray)
{
    $orderByStatements = '';
    foreach ($evaluacionesArray as $puestoId => $evaluaciones) {
        foreach ($evaluaciones as $evaluacion) {
            $orderByStatements .= "$evaluacion DESC, ";
        }
    }

    return rtrim($orderByStatements, ', ');
}




    //!Funcion Buscar
 public static function buscarAPI()
 {
    
     $sql = "SELECT 
                ci.ing_id,
                ci.ing_codigo,
                ci.ing_puesto,
                pue.pue_nombre,
                cont.cont_nombre,
                ci.ing_situacion
            FROM cont_ingresos ci
            JOIN cont_aspirantes asp ON ci.ing_aspirante = asp.asp_id
            JOIN cont_puestos pue ON ci.ing_puesto = pue.pue_id
            JOIN contingentes cont ON ci.ing_contingente = cont.cont_id";

     try {

         $ingresos = Ingreso::fetchArray($sql);

         echo json_encode($ingresos);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error',
             'codigo' => 0
         ]);
     }
 }

 public static function buscarRequisitoPuestoAPI()
 {
     $puestoId = $_GET['ing_puesto'];
     $ingId = $_GET['ing_id'];
     
     try {
         if ($puestoId === null) {
             echo json_encode([
                 'mensaje' => 'Falta el ID del Puesto',
                 'codigo' => 0
             ]);
             return;
         }
 
         $sql = "SELECT 
                    ar.asig_req_id,
                    r.req_nombre,
                    p.pue_nombre,
                    ca.apro_id,
                    ca.apro_situacion,
                    ci.ing_puesto,
                    ci.ing_id AS ing_id
                FROM 
                    cont_asig_requisitos ar
                JOIN
                    cont_requisitos r ON ar.asig_req_requisito = r.req_id
                JOIN
                    cont_puestos p ON ar.asig_req_puesto = p.pue_id
                JOIN
                    cont_ingresos ci ON ar.asig_req_puesto = ci.ing_puesto
                LEFT JOIN
                    cont_req_aprovado ca ON ar.asig_req_id = ca.apro_requisito AND ci.ing_id = ca.apro_ingreso
                WHERE
                    ar.asig_req_puesto = $puestoId
                    AND ar.asig_req_situacion = 1
                    AND ci.ing_id = $ingId";
 
         // Ejecutar la consulta y obtener las misiones del contingente.
         $asigrequisitos = AsigRequisito::fetchArray($sql);
 
         echo json_encode($asigrequisitos);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error al obtener los Requisitos del puesto',
             'codigo' => 0
         ]);
     }
 }
 


 //!Funcion Guardar
public static function guardarAPI() {
    try {
        $Id_Ingreso = $_GET['ing_id'];
        $Id_Requisito = $_GET['asig_req_id'];

        
        
        // ! Aca se recibe los datos que se guardaran en otra tabla.
        $datos['apro_ingreso'] = $Id_Ingreso;
        $datos['apro_requisito'] = $Id_Requisito;
        
        $Requisito_Aprovado = new RequisitoAprovado($datos);
        $result = $Requisito_Aprovado->guardar();

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


 //!Funcion desaprovar
 public static function desaprovarAPI()
 {
     $apro_id = $_POST['apro_id'];
     try {
         $requisito = RequisitoAprovado::find($apro_id);
 
         // Verificar si la evaluación existe
         if ($requisito) {
             $requisito->apro_situacion = 0;
             $resultado = $requisito->actualizar();
 
             if ($resultado['resultado'] == 1) {
                 echo json_encode([
                     'mensaje' => 'Requisito Desaprovado correctamente',
                     'codigo' => 1
                 ]);
             } else {
                 echo json_encode([
                     'mensaje' => 'Ocurrió un error al actualizar el Requisito',
                     'codigo' => 0
                 ]);
             }
         } else {
             echo json_encode([
                 'mensaje' => 'No se encontró el Requisito con el ID proporcionado',
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
 
 //!Funcion reaprovar
 public static function aprovarAPI()
 {
     $apro_id = $_POST['apro_id'];
     try {
         $requisito = RequisitoAprovado::find($apro_id);
 
         // Verificar si la evaluación existe
         if ($requisito) {
             $requisito->apro_situacion = 1;
             $resultado = $requisito->actualizar();
 
             if ($resultado['resultado'] == 1) {
                 echo json_encode([
                     'mensaje' => 'Requisito Aprovado correctamente',
                     'codigo' => 1
                 ]);
             } else {
                 echo json_encode([
                     'mensaje' => 'Ocurrió un error al aprovar el requisito',
                     'codigo' => 0
                 ]);
             }
         } else {
             echo json_encode([
                 'mensaje' => 'No se encontró el requisito con el ID proporcionado',
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
}
