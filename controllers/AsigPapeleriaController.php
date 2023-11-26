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
        $asigpapeleria = AsigPapeleria::all();

        $router->render('asigpapeleria/index', [
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
//!Funcion Select requisitoelria
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

    $sql = "SELECT DISTINCT pue_id, pue_nombre
    FROM cont_puestos 
    WHERE pue_situacion = 1";
    

     try {
         $asigpapeleria = Puesto::fetchArray($sql);

         echo json_encode($asigpapeleria);
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
         pap.pap_id AS requisito_id,
         pap.pap_nombre AS documento_nombre,
         asig_pap.asig_pap_id
     FROM 
     cont_papeleria pap
     JOIN 
         cont_asig_papeleria asig_pap ON pap.pap_id = asig_pap.asig_pap_nombre
     JOIN 
         cont_puestos pue ON asig_pap.asig_pap_puesto = pue.pue_id
     WHERE 
         pue.pue_id = $puestoId AND asig_pap.asig_pap_situacion = 1
         ORDER BY documento_nombre ASC";
 
         // Ejecutar la consulta y obtener las misiones del contingente.
         $asigpapeleria = AsigPapeleria::fetchArray($sql);
 
         echo json_encode($asigpapeleria);
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
        $asigPapeleriaData = $_POST;

        $asigPapeleria = new AsigPapeleria($asigPapeleriaData);
        $resultado = $asigPapeleria->crear();

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
         $asig_pap_id = $_POST['asig_pap_id'];
         $asigPapeleria = AsigPapeleria::find($asig_pap_id);
         $asigPapeleria->asig_pap_situacion = 0;
         $resultado = $asigPapeleria->actualizar();

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
