<?php

namespace Controllers;

use Exception;
use Model\Personal;
use Model\Aspirante;
use Model\Usuario;
use Model\Grado;
use Model\Arma;
use Model\Puesto;
use MVC\Router;


class UsuarioController
{
    public static function index(Router $router)
    {
        $puestos = static::buscarPuesto();
        $router->render('usuarios/index', [
            'puestos' => $puestos,
        ]);
    }

//!Funcion Select Puestos
    public static function buscarPuesto()
    {
        $sql = "SELECT * FROM cont_puestos where pue_situacion = 1";

        try {
            $puestos = Puesto::fetchArray($sql);
            return $puestos;
        } catch (Exception $e) {
            return [];
        }
    }
//!Funcion Buscar
public static function buscarAPI()
{
    $catalogo = $_GET['per_catalogo'];
    
    try {
        if ($catalogo != '') {
            $sql = "SELECT 
                per_catalogo,
                per_nom1,
                per_nom2,
                per_ape1,
                per_ape2,
                per_dpi,
                CASE 
                    WHEN per_sexo = 'M' THEN 'MASCULINO'
                    WHEN per_sexo = 'F' THEN 'FEMENINO'
                    ELSE 'DESCONOCIDO'
                END AS per_sexo,
                grados.gra_desc_md,
                armas.arm_desc_md
            FROM mper
            INNER JOIN grados ON mper.per_grado = grados.gra_codigo
            INNER JOIN armas ON mper.per_arma = armas.arm_codigo
            where per_catalogo = $catalogo
            ";
            $usuarios = Usuario::fetchArray($sql);
        
            echo json_encode($usuarios);
        } else {
            echo json_encode([
                'mensaje' => 'Por favor, ingrese un número de catálogo',
                'codigo' => 1
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



//!Funcion Guardar
public static function guardarAPI(){
    try {
        $cat = $_POST['per_catalogo'];
      
        $personal = new Personal($_POST);
   
        $resultado = $personal->crear();


        $id_aspirante =$resultado['id'];
        echo json_encode($id_aspirante);
exit;
        


       // $ingresos = new Aspirante ();

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
            'mensaje' => 'El Aspirante ya fue Inscrito',
            'codigo' => 2
        ]);
    }
}

        // public static function enviarAPI(){
        //     try {
        //         $aspirante = new Aspirante($_POST);
        //         $resultado = $aspirante->crear();

        //         if ($resultado['resultado'] == 1) {
        //             echo json_encode([
        //                 'mensaje' => 'Registro enviado correctamente',
        //                 'codigo' => 1
        //             ]);
        //         } else {
        //             echo json_encode([
        //                 'mensaje' => 'Ocurrió un error al enviar',
        //                 'codigo' => 0
        //             ]);
        //         }
        //     } catch (Exception $e) {
        //         echo json_encode([
        //             'detalle' => $e->getMessage(),
        //             'mensaje' => 'El Aspirante ya fue Inscrito',
        //             'codigo' => 2
        //         ]);
        //     }
        // }

}