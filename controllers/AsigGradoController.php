<?php

namespace Controllers;

use Exception;
use Model\Puesto;
use Model\AsigGrado;
use Model\Grado;
use MVC\Router;

class AsigGradoController {
    public static function index(Router $router){

        // $puesto = Puesto::all();
        $grados = static::buscarGrados();
        $puestos = static::buscarPuesto();

        $router->render('asiggrados/index', [
            'grados' => $grados,
            'puestos' => $puestos,
        ]);
    }

    //!Funcion Select Puestos
    public static function buscarPuesto()
    {
        $grado = $_GET['pue_grado'];


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
    //!Funcion Select Grados
    public static function buscarGrados()
    {
        $sql = "SELECT * FROM grados 
                ORDER BY gra_desc_lg ASC";

        try {
            $grados = Grado::fetchArray($sql);
            return $grados;
        } catch (Exception $e) {
            return [];
        }
    }

 //!Funcion Buscar
 public static function buscarAPI()
 {  
     $sql = "SELECT pue_id, pue_nombre
     FROM cont_puestos 
     WHERE pue_situacion = 1
     ORDER BY pue_nombre ASC";

     try {

         $puestos = Puesto::fetchArray($sql);

         echo json_encode($puestos);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error',
             'codigo' => 0
         ]);
     }
 }

 
 public static function buscarGradosPuestosAPI()
 {
     $puestoId = $_GET['pue_id'];
     
     try {
         if ($puestoId === null) {
             echo json_encode([
                 'mensaje' => 'Falta el ID del contingente',
                 'codigo' => 0
             ]);
             return;
         }
 
         // Consulta SQL para obtener las misiones de un contingente específico.
         $sql = "SELECT
                    pue.pue_id,
                    pue.pue_nombre,
                    gra.gra_desc_lg,
                    agp.asig_grado_id
                FROM
                    cont_puestos pue
                JOIN
                    asig_grado_puesto agp ON pue.pue_id = agp.asig_puesto
                JOIN
                    grados gra ON agp.asig_grado = gra.gra_codigo
                WHERE
                    pue.pue_id = $puestoId
                    AND pue.pue_situacion = 1
                    AND agp.asig_grado_situacion = 1";
 
         // Ejecutar la consulta y obtener las misiones del contingente.
         $asiggrado = AsigGrado::fetchArray($sql);
 
         echo json_encode($asiggrado);
     } catch (Exception $e) {
         echo json_encode([
             'detalle' => $e->getMessage(),
             'mensaje' => 'Ocurrió un error al obtener las Grados de este puesto',
             'codigo' => 0
         ]);
     }
 }

  //!Funcion Guardar
 public static function guardarAPI(){ 

    try {
        $codigo = $_POST['pue_id'];
        $grado = new AsigGrado($_POST);
        $resultado = $grado->crear();

        if ($resultado['resultado'] == 1) {
            echo json_encode([
                'mensaje' => 'Grado Asignado correctamente',
                'codigo' => 1
            ]);
        } else {
            echo json_encode([
                'mensaje' => 'Ocurrió un error',
                'codigo' => 0
            ]);
        }
        // echo json_encode($resultado);
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
         $asig_grado_id = $_POST['asig_grado_id'];
         $asigGrado = AsigGrado::find($asig_grado_id);
         $asigGrado->asig_grado_situacion = 0;
         $resultado = $asigGrado->actualizar();

         if($resultado['resultado'] == 1){
             echo json_encode([
                 'mensaje' => 'Grado Removido Correctamente',
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
         $puestoData = $_POST;
 
         // Validar campos vacíos
         foreach ($puestoData as $campo => $valor) {
             if (empty($valor)) {
                 echo json_encode([
                     'mensaje' => 'Llene Todos Los Campos',
                     'codigo' => 0
                 ]);
                 return;
             }
         }

         $puestoData['pue_situacion'] = 1;
 
         $puesto = new Puesto($puestoData);
         $resultado = $puesto->actualizar();
 
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
