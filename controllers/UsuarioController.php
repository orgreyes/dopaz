<?php

namespace Controllers;

use Exception;
use Model\Ingreso;
use Model\Contingente;
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
        $contingentes = static::buscaContingentes();
        $grados = static::buscarGrados();
        $puestos = static::buscarPuesto();
        $router->render('usuarios/index', [
            'contingentes' => $contingentes,
            'puestos' => $puestos,
            'grados' => $grados,
        ]);
    }

//!Funcion Select Contingentes
public static function buscaContingentes()
{
    $sql = "SELECT *
    FROM contingentes
    WHERE cont_situacion = 1
        AND cont_fecha_inicio > TODAY";

    try {
        $contingentes = Contingente::fetchArray($sql);
        return $contingentes;
    } catch (Exception $e) {
        return [];
    }
}
//!Funcion Select Grados
    public static function buscarGrados()
    {
        $sql = "SELECT * FROM grados
        ORDER BY gra_desc_md ASC";

        try {
            $grados = Grado::fetchArray($sql);
            return $grados;
        } catch (Exception $e) {
            return [];
        }
    }
//!Funcion Select Puestos
    public static function buscarPuesto()
    {
        $sql = "SELECT * FROM cont_puestos
         where pue_situacion = 1
         ORDER BY pue_nombre ASC";

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
        
        $puesto = $_POST['ing_puesto'];
        $contingente = $_POST['asig_contingente'];
        $fecha_hoy = date("d/m/Y");
      
        $aspirante = new Aspirante($_POST);
        $resultado = $aspirante->crear();

//!Aca se captura el id que se crea.
        $id_aspirante =$resultado['id'];


//!Aca se recibe los datos que se guardaran en otra tabla.
        $datos ['ing_aspirante'] = $id_aspirante;
        $datos ['ing_fecha_cont'] = $fecha_hoy;
        $datos ['ing_puesto'] = $puesto;
        $datos ['ing_contingente'] = $contingente;

        $ingresos = new Ingreso ($datos);
        $result = $ingresos->guardar();
        echo json_encode($result);

    } catch (Exception $e) {
        echo json_encode([
            'detalle' => $e->getMessage(),
            'mensaje' => 'El Aspirante ya fue Inscrito',
            'codigo' => 2
        ]);
    }
}

}