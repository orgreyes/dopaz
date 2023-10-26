<?php

namespace Controllers;

use Exception;
use Model\Aspirante;
use Model\Usuario;
use Model\Grado;
use Model\Arma;
use MVC\Router;

class UsuarioController
{

    public static function index(Router $router)
    {
        // $grados = static::buscarGrado();
        // $armas = static::buscarArma();

        $router->render('usuarios/index', [
        ]);
        // 'grados' => $grados,
        // 'armas' => $armas,
    }

    // public static function buscarGrado()
    // {
    //     $sql = "SELECT * FROM grados where gra_clase = 1";

    //     try {
    //         $grados = Grado::fetchArray($sql);
    //         return $grados;
    //     } catch (Exception $e) {
    //         return [];
    //     }
    // }

    // public static function buscarArma()
    // {
    //     $sql = "select arm_desc_md from armas";

    //     try {
    //         $armas = Arma::fetchArray($sql);
    //         return $armas;
    //     } catch (Exception $e) {
    //         return [];
    //     }
    // }
    public static function buscarAPI()
    {

        $per_catalogo = $_GET['per_catalogo'];

        $sql = "SELECT 
        mper.per_catalogo,
        mper.per_nom1,
        mper.per_nom2,
        mper.per_ape1,
        mper.per_ape2,
        mper.per_dpi,
        mper.per_sexo,
        grados.gra_desc_md,
        armas.arm_desc_md
    FROM mper
    INNER JOIN grados ON mper.per_grado = grados.gra_codigo
    INNER JOIN armas ON mper.per_arma = armas.arm_codigo
    ";

        $conditions = [];

        // Verificar y agregar las condiciones de búsqueda según los parámetros proporcionados
        if (!empty($per_catalogo)) {
            $conditions[] = "mper.per_catalogo = '$per_catalogo'";
        }

        // Comprobar si hay condiciones y agregarlas a la consulta
        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }


        try {
            $usuarios = Usuario::fetchArray($sql);
            echo json_encode($usuarios);
        } catch (Exception $e) {
            echo json_encode([
                'detalle' => $e->getMessage(),
                'mensaje' => 'Ocurrió un error',
                'codigo' => 0
            ]);
        }
    }
}