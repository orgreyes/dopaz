<?php

namespace Controllers;

use Exception;
use Model\AsigRequisito;
use Model\Puesto;
use Model\Requisito;
use MVC\Router;

class AsigRequisitoController {
    public static function index(Router $router){
        $puestos = static::buscarPuestos();
        $requisitos = static::buscarRequisito();
        $asigrequisitos = AsigRequisito::all();

        $router->render('asigrequisitos/index', [
            'puestos' => $puestos,
            'requisitos' => $requisitos,
        ]);
    }
    
//!Funcion Select Puestos
public static function buscarPuestos()
{
    $sql = "SELECT pue_id, pue_nombre
    FROM cont_puestos 
    WHERE pue_situacion = 1
    ORDER BY pue_nombre ASC";

    try {
        $puestos = Puesto::fetchArray($sql);
        return $puestos;
    } catch (Exception $e) {
        return [];
    }
}
//!Funcion Select requisitoelria
public static function buscarRequisito()
{
    $sql = "SELECT *
    FROM cont_requisitos
    WHERE req_situacion = 1
    ORDER BY req_nombre";

    try {
        $requisitos = Requisito::fetchArray($sql);
        return $requisitos;
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


 public static function buscarRequisitoPuestoAPI()
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
                        MIN(pue.pue_id) AS puesto_id,
                        MIN(pue.pue_nombre) AS puesto_nombre,
                        MIN(req.req_id) AS requisito_id,
                        MIN(req.req_nombre) AS requisito_nombre,
                        MIN(asig_req.asig_req_id) AS asig_req_id
                    FROM 
                        cont_requisitos req
                    JOIN 
                        cont_asig_requisitos asig_req ON req.req_id = asig_req.asig_req_requisito
                    JOIN 
                        cont_puestos pue ON asig_req.asig_req_puesto = pue.pue_id
                    WHERE 
                        pue.pue_id = $puestoId AND asig_req.asig_req_situacion = 1
                    GROUP BY 
                        req.req_nombre
                    ORDER BY 
                        requisito_nombre ASC";
 
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
public static function guardarAPI()
{
    try {
        $asigRequisitoData = $_POST;

        $asigRequisito = new AsigRequisito($asigRequisitoData);
        $resultado = $asigRequisito->crear();

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
         $asig_req_id = $_POST['asig_req_id'];
         $asigRequisito = AsigRequisito::find($asig_req_id);
         $asigRequisito->asig_req_situacion = 0;
         $resultado = $asigRequisito->actualizar();

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
