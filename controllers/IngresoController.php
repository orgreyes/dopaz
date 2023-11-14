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
                    ca.apro_situacion,
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
        // ! Si hay una excepción, envía una respuesta JSON de error
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'El Aspirante ya fue Inscrito',
            'codigo' => 2
        ]);
    }
}

}
