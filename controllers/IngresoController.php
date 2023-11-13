<?php

namespace Controllers;

use Exception;
use Model\Ingreso;
use Model\AsigRequisito;
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
                    ar.asig_req_aprovada,
                    p.pue_nombre
                FROM 
                    cont_asig_requisitos ar
                JOIN
                    cont_requisitos r ON ar.asig_req_requisito = r.req_id
                JOIN
                    cont_puestos p ON ar.asig_req_puesto = p.pue_id
                WHERE
                    ar.asig_req_puesto = $puestoId
                    AND ar.asig_req_situacion = 1";
 
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
 
 //!Funcion Modificar

  public static function modificarAPI(){
    try{
        $asig_req_id = $_POST['asig_req_id'];
        $requisito = AsigRequisito::find($asig_req_id);
        $requisito->asig_req_aprovada = 2;
        $resultado = $requisito->actualizar();

        if($resultado['resultado'] == 1){
            echo json_encode([
                'mensaje' => 'Requisito Aprovado correctamente',
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
