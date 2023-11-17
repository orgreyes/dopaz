<?php

namespace Controllers;

use Exception;
use Model\Ingreso;
use Model\Aspirante;
use Model\Contingente;
use Model\Grado;
use Model\Arma;
use Model\Puesto;
use MVC\Router;

class AspiranteController
{
    public static function index(Router $router)
    {
        $contingentes = static::buscaContingentes();
        $grados = static::buscarGrados();
        $puestos = static::buscarPuestoAPI();
        $router->render('aspirantes/index', [
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
    public static function buscarPuestoAPI()
    {
    $Grado = $_GET['pue_grado'];
    
    try {
        $sql = "SELECT p.*
        FROM cont_puestos p
        JOIN asig_grado_puesto agp ON p.pue_id = agp.asig_puesto
        JOIN grados g ON agp.asig_grado = g.gra_codigo
        WHERE p.pue_situacion = 1
        AND g.gra_codigo = $Grado;";
        

            $Puestos = Puesto::fetchArray($sql);
        echo json_encode($Puestos);
            return $Puestos;
        } catch (Exception $e) {
            return [];
        }
    }
//!Funcion Buscar
public static function buscarAPI()
{
    $catalogo = $_GET['asp_catalogo'];
    
    try {
        if ($catalogo != '') {
            $sql = "SELECT 
            ca.asp_id,
            ca.asp_catalogo,
            ca.asp_dpi, 
            ca.asp_nom1, 
            ca.asp_nom2, 
            ca.asp_ape1, 
            ca.asp_ape2, 
            CASE
                WHEN ca.asp_genero = 'M' THEN 'MASCULINO'
                WHEN ca.asp_genero = 'F' THEN 'FEMENINO'
                ELSE 'OTRO'
            END AS asp_genero_desc,
            mper.per_arma,
            g.gra_codigo AS per_grado_id, -- Cambiado: Ahora se selecciona el ID del grado
            a.arm_desc_md AS arma
        FROM 
            cont_aspirantes ca
        LEFT JOIN 
            mper ON ca.asp_catalogo = mper.per_catalogo
        LEFT JOIN
            grados g ON mper.per_grado = g.gra_codigo
        LEFT JOIN
            armas a ON mper.per_arma = a.arm_codigo
        WHERE 
            ca.asp_catalogo = $catalogo";

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
public static function guardarAPI() {
    try {
        $codigo = $_POST['ing_codigo'];
        $puesto = $_POST['ing_puesto'];
        $Id_Aspirante = $_POST['asp_id'];
        $contingente = $_POST['asig_contingente'];
        $fecha_hoy = date("d/m/Y");
        
        // ! Aca se recibe los datos que se guardaran en otra tabla.
        $datos['ing_codigo'] = $codigo;
        $datos['ing_puesto'] = $puesto;
        $datos['ing_aspirante'] = $Id_Aspirante;
        $datos['ing_contingente'] = $contingente;
        $datos['ing_fecha_cont'] = $fecha_hoy;
        
        $ingresos = new Ingreso($datos);
        $result = $ingresos->guardar();

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