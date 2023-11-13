<?php

namespace Controllers;

use Exception;
use Model\Puesto;
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

// SELECT pue_id, pue_nombre
// FROM cont_puestos 
// WHERE pue_grado = $grado
// AND pue_situacion = 1
// ORDER BY pue_nombre ASC

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
     $sql = "SELECT p.pue_id, p.pue_nombre, g.gra_desc_md
     FROM cont_puestos p
     INNER JOIN grados g ON p.pue_grado = g.gra_codigo
     WHERE p.pue_situacion = 1
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

  //!Funcion Guardar
 public static function guardarAPI(){ 

    try {
        $codigo = $_POST['pue_id'];
        $puesto = new Puesto($_POST);
        $resultado = $puesto->crear();

        if ($resultado['resultado'] == 1) {
            echo json_encode([
                'mensaje' => 'Puesto guardado correctamente',
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
         $pue_id = $_POST['pue_id'];
         $puesto = Puesto::find($pue_id);
         $puesto->pue_situacion = 0;
         $resultado = $puesto->actualizar();

         if($resultado['resultado'] == 1){
             echo json_encode([
                 'mensaje' => 'Puesto Eliminado Correctamente',
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
