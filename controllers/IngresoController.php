<?php

namespace Controllers;

use Exception;
use Model\Ingreso;
use Model\Pdf;
use Model\Aprovado;
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

    $pue_id = $_GET['ing_puesto'];
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
           p.pue_id = $pue_id";
           
   
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
                                i.ing_id,
                                i.ing_codigo,
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
                                i.ing_puesto = $pue_id
                            AND i.ing_situacion = 2
                            AND YEAR(i.ing_fecha_cont) IN (YEAR(CURRENT), YEAR(CURRENT) + 1)
                            GROUP BY i.ing_id, i.ing_codigo, i.ing_aspirante, i.ing_puesto, c.cont_nombre, p.pue_nombre
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
 private static function buildMaxCaseStatements($evaluacionesArray){
    $maxCaseStatements = '';
    foreach ($evaluacionesArray as $puestoId => $evaluaciones) {
        foreach ($evaluaciones as $evaluacion) {
            $maxCaseStatements .= "MAX(CASE WHEN e.eva_nombre = '$evaluacion' THEN r.res_nota END) AS $evaluacion,\n";
        }
    }

    return rtrim($maxCaseStatements, ",\n");
 }
 // Función para construir la cláusula ORDER BY
 private static function buildOrderByStatements($evaluacionesArray){
    $orderByStatements = '';
    foreach ($evaluacionesArray as $puestoId => $evaluaciones) {
        foreach ($evaluaciones as $evaluacion) {
            $orderByStatements .= "$evaluacion DESC, ";
        }
    }

    return rtrim($orderByStatements, ', ');
    }


 //!Funcion Buscar todos los puestos solicitados.
 public static function buscarPuestosAPI()
 {
    $sql = "SELECT DISTINCT
                pue_id AS ing_puesto,
                pue_nombre AS puesto_nombre
            FROM
                cont_ingresos 
            JOIN
                cont_puestos ON ing_puesto = pue_id
            WHERE
                ing_situacion = 1
                AND (YEAR(ing_fecha_cont) = YEAR(TODAY) OR YEAR(ing_fecha_cont) = YEAR(TODAY) + 1)";
    
    try {
        $ingresos = Ingreso::fetchArray($sql);
        header('Content-Type: application/json');
        echo json_encode($ingresos);
    } catch (Exception $e) {
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'Ocurrió un error',
            'codigo' => 0
        ]);
    }
}

//!Funcion Buscar todos los puestos en la fase dos, donde estan las notas.
public static function buscarPuestosNotasAPI()
{
    $sql = "SELECT DISTINCT
                pue_id AS ing_puesto,
                pue_nombre AS puesto_nombre
            FROM
                cont_ingresos 
            JOIN
                cont_puestos ON ing_puesto = pue_id
            WHERE
                ing_situacion = 2
                AND (YEAR(ing_fecha_cont) = YEAR(TODAY) OR YEAR(ing_fecha_cont) = YEAR(TODAY) + 1)";
                
    try {
        $ingresos = Ingreso::fetchArray($sql);
        header('Content-Type: application/json');
        echo json_encode($ingresos);
    } catch (Exception $e) {
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'Ocurrió un error',
            'codigo' => 0
        ]);
    }
}

//!Funcion Buscar todos los puestos solicitados.
public static function buscarPuestosRequisitosAPI(){
    $sql = "SELECT DISTINCT
                pue_id AS ing_puesto,
                pue_nombre AS puesto_nombre
            FROM
                cont_ingresos 
            JOIN
                cont_puestos ON ing_puesto = pue_id
            WHERE
                ing_situacion = 3
                AND (YEAR(ing_fecha_cont) = YEAR(TODAY) OR YEAR(ing_fecha_cont) = YEAR(TODAY) + 1)";
    
    try {
        $ingresos = Ingreso::fetchArray($sql);
        header('Content-Type: application/json');
        echo json_encode($ingresos);
    } catch (Exception $e) {
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'Ocurrió un error',
            'codigo' => 0
        ]);
    }
}

//!Funcion Buscar a Personal que Solicita iniciar proceso de seleccion.
public static function buscarTodoAPI(){
       try {
       
       $sql = "SELECT
                    TRIM(asp_nom1 || ' ' || NVL(asp_nom2, '') || ' ' || asp_ape1 || ' ' || NVL(asp_ape2, '')) AS nombre_aspirante,
                    cp.pue_nombre AS nombre_puesto,
                    c.cont_nombre AS nombre_contingente,
                    ci.ing_id
                FROM
                    cont_ingresos ci
                JOIN
                    cont_aspirantes a ON ci.ing_aspirante = a.asp_id
                JOIN
                    cont_puestos cp ON ci.ing_puesto = cp.pue_id
                JOIN
                    contingentes c ON ci.ing_contingente = c.cont_id
                WHERE
                    ci.ing_situacion = 1
                    AND YEAR(ci.ing_fecha_cont) IN (YEAR(CURRENT), YEAR(CURRENT) + 1)";
  
  
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

//!Funcion Buscar a Personal que Solicita al pulsar uno de los botones de los puestos
public static function buscarSolicitudesAPI(){
    $puestoId = $_GET['ing_puesto'];
       try {
      
       $sql = "SELECT
       TRIM(asp_nom1 || ' ' || NVL(asp_nom2, '') || ' ' || asp_ape1 || ' ' || NVL(asp_ape2, '')) AS nombre_aspirante,
       cp.pue_nombre AS nombre_puesto,
       c.cont_nombre AS nombre_contingente,
       ci.ing_id
   FROM
       cont_ingresos ci
   JOIN
       cont_aspirantes a ON ci.ing_aspirante = a.asp_id
   JOIN
       cont_puestos cp ON ci.ing_puesto = cp.pue_id
   JOIN
       contingentes c ON ci.ing_contingente = c.cont_id
   WHERE
       ci.ing_situacion = 1
       AND YEAR(ci.ing_fecha_cont) IN (YEAR(CURRENT), YEAR(CURRENT) + 1)
       AND ci.ing_puesto = $puestoId";
  
  
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

//!Funcion Buscar Personal a seleccionar cuando Apruebe los REQUISITOS
public static function buscarAPI(){

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
            JOIN contingentes cont ON ci.ing_contingente = cont.cont_id
            WHERE ci.ing_situacion = 3";

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
//!Funcion Buscar Personal a seleccionar cuando Apruebe los REQUISITOS
public static function buscarRequisitosPorPuestoAPI(){
    $puestoId = $_GET['ing_puesto'];

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
            JOIN contingentes cont ON ci.ing_contingente = cont.cont_id
            WHERE ci.ing_situacion = 3
            AND ci.ing_puesto = $puestoId";

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

//!Funcion Buscar Los requisitos que se deben cumplir
public static function buscarDocumentacionAPI(){
    $ingId = $_GET['ing_id'];
    
    try {
        if ($ingId === null) {
            echo json_encode([
                'mensaje' => 'Falta el ID del Puesto',
                'codigo' => 0
            ]);
            return;
        }

        $sql = "SELECT pdf_ruta AS nombre_pdf, 
                        pdf_ingreso AS ing_id
                FROM cont_pdf
                WHERE pdf_ingreso = $ingId";

        $Pdf = Pdf::fetchArray($sql);

        echo json_encode($Pdf);
    } catch (Exception $e) {
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'Ocurrió un error al obtener los Requisitos del puesto',
            'codigo' => 0
        ]);}
}

//!Funcion Buscar Los requisitos que se deben cumplir
public static function buscarRequisitoPuestoAPI(){
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
                    r.req_id,
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
         ]);}
}
 
 //!Funcion Para Aprovar los requisitos por primera vez
public static function guardarAPI() {
    try {
        $Id_Ingreso = $_GET['ing_id'];
        $Id_Requisito = $_GET['asig_req_id'];
        $req_id = $_GET['req_id'];

        
        // ! Aca se recibe los datos que se guardaran en otra tabla.
        $datos['apro_ingreso'] = $Id_Ingreso;
        $datos['apro_requisito'] = $Id_Requisito;
        $datos['apro_id_requisito'] = $req_id;
        
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

 //!Funcion desaprovar Requisitos
 public static function desaprovarAPI(){
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
 
 //!Funcion reaprovar Requisitos
 public static function aprovarAPI(){
     $apro_id = $_POST['apro_id'];
     try {
         $requisito = RequisitoAprovado::find($apro_id);
 
         // Verificar si la evaluación existe
         if ($requisito) {
             $requisito->apro_situacion = 1;
             $resultado = $requisito->actualizar();
 
             if ($resultado['resultado'] == 1) {
                 echo json_encode([
                     'mensaje' => 'Calificado correctamente',
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

//!Funcion Para Iniciar el proceso del aspiratne y que pase al proceso de seleccion por notas
public static function iniciarProcesoAPI(){
    try{
        $ing_id = $_GET['ing_id'];
        $evaluacion = Ingreso::find($ing_id);
        $evaluacion->ing_situacion = 2;
        $resultado = $evaluacion->actualizar();

        if($resultado['resultado'] == 1){
            echo json_encode([
                'mensaje' => 'El Aspirante inicio el proceso correctamente',
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

//!Funcion Para Aprovar el proceso por notas
public static function seleccionPorNotaAPI(){
    try{
        $ing_id = $_GET['ing_id'];
        $evaluacion = Ingreso::find($ing_id);
        $evaluacion->ing_situacion = 3;
        $resultado = $evaluacion->actualizar();

        if($resultado['resultado'] == 1){
            echo json_encode([
                'mensaje' => 'El Aspirante inicio el proceso correctamente',
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

//!Funcion Para Ver PDF
public static function VerPdf(Router $router)
    {

        $ruta = base64_decode(base64_decode(base64_decode($_GET['ruta'])));
        // echo json_encode([$ruta]);
        // exit;
        $router->printPDF($ruta);
}

// Función para aprobar plaza y obtener requisitos
public static function aprobarPlazaAPI() {
    try {
        // Verifica si se ha proporcionado el parámetro 'ing_id' en la solicitud GET
        if (!isset($_GET['ing_id'])) {
            throw new Exception('El parámetro "ing_id" no se ha proporcionado.');
        }

        $ing_id = $_GET['ing_id'];

        // Consulta para obtener los requisitos asignados al puesto
        $sql = "SELECT req_nombre
                FROM cont_asig_requisitos car
                JOIN cont_requisitos cr ON car.asig_req_requisito = cr.req_id
                JOIN cont_ingresos ci ON car.asig_req_puesto = ci.ing_puesto
                WHERE ci.ing_id = $ing_id";

        $requisitosAsignados = Ingreso::fetchArray($sql);

        // Consulta para obtener los requisitos aprobados para el ingreso
        $sql2 = "SELECT cr.req_nombre
                FROM cont_req_aprovado cra
                JOIN cont_requisitos cr ON cra.apro_id_requisito = cr.req_id
                WHERE cra.apro_ingreso = $ing_id";

        $requisitosAprobados = RequisitoAprovado::fetchArray($sql2);

        // Combina los resultados en un solo array asociativo
        $resultado = [
            'requisitos_asignados' => $requisitosAsignados,
            'requisitos_aprobados' => $requisitosAprobados,
        ];

        // Devuelve la respuesta como JSON
        echo json_encode($resultado);
    } catch (Exception $e) {
        // Maneja las excepciones y devuelve un mensaje de error
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'Ocurrió un error',
            'codigo' => 0
        ]);
    }
}

public static function guardarPlazaAPI() {
    try {

        $ing_id = $_GET['ing_id'];

        $aprovado = Ingreso::find($ing_id);
                $aprovado->ing_situacion = 4;
                $resultado = $aprovado->actualizar();
        
        $datos['apro_asp'] = $ing_id;
        $Aprovado = new Aprovado($datos);
        $result = $Aprovado->guardar();

        // ! Solo envía una respuesta JSON al final
        if ($result['resultado'] == 1) {
            echo json_encode([
                'mensaje' => 'Aspirante Aprovado',
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

}

