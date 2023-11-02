<?php

namespace Controllers;

use Exception;
use Model\Ingreso;
use Model\Aspirante;
use Model\Grado;
use Model\Arma;
use Model\Puesto;
use MVC\Router;

class AspiranteController
{
    public static function index(Router $router)
    {
        $puestos = static::buscarPuesto();
        $router->render('aspirantes/index', [
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
            p.per_id,
            p.per_dpi,
            p.per_nom1,
            p.per_nom2,
            p.per_ape1,
            p.per_ape2,
            p.per_grado, 
            p.per_arma, 
            p.per_genero,
            p.per_catalogo,
            c.pue_nombre AS nombre_puesto
        FROM cont_personal p
        LEFT JOIN cont_puestos c ON p.per_puesto = c.pue_id
        WHERE p.per_catalogo = $catalogo
            ";
            $aspirantes = Aspirante::fetchArray($sql);
        
            echo json_encode($aspirantes);
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
        $aspirante = new Aspirante($_POST);
        $resultado = $aspirante->crear();

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
        // echo json_encode($resultado);
    } catch (Exception $e) {
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'El Aspirante ya fue Inscrito',
            'codigo' => 0
        ]);
    }
}
}