<?php

namespace Controllers;

use Exception;
use Model\Aprobado;
use Model\Ingreso;
use Model\Usuario;
use Model\Puesto;
use Model\Contingente;
use MVC\Router;

class AprobadoController {
    public static function index(Router $router){

        $contingentes = static::buscaContingentes();
        $aprobados = Aprobado::all();

        $router->render('aprobados/index', [
            'contingentes' => $contingentes,
        ]);
    }
    
//!Funcion Select Contingentes
public static function buscaContingentes(){
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

  //!Funcion Buscar
  public static function buscarPorContingenteAPI(){
        $cont_id = $_GET['cont_id'];
 

        $sql = "SELECT DISTINCT
        ca.apro_asp AS asp_id,
        TRIM(ca1.asp_nom1) || ' ' || TRIM(ca1.asp_nom2) || ' ' || TRIM(ca1.asp_ape1) || ' ' || TRIM(ca1.asp_ape2) AS nombre_aspirante,
        c.cont_id,
        c.cont_nombre AS nombre_contingente,
        c.cont_fecha_inicio,
        c.cont_fecha_final AS fecha_final_contingente,
        ci.ing_id,
        p.pue_nombre AS nombre_puesto
    FROM
        cont_aprobados ca
    JOIN
        cont_ingresos ci ON ca.apro_asp = ci.ing_aspirante
    JOIN
        cont_puestos p ON ci.ing_puesto = p.pue_id
    JOIN
        contingentes c ON ci.ing_contingente = c.cont_id
    JOIN
        cont_aspirantes ca1 ON ca.apro_asp = ca1.asp_id
    WHERE
        c.cont_id = $cont_id
        AND ci.ing_situacion = 4
    ORDER BY
        p.pue_nombre";
    
 
      try {
 
          $evaluaciones = Aprobado::fetchArray($sql);
 
          echo json_encode($evaluaciones);
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
        $ing_id = $_POST['ing_id'];
        // echo json_encode([$_POST]);
        // exit;
        $aprobado = Ingreso::find($ing_id);
        $aprobado->ing_situacion = 1;
        $resultado = $aprobado->actualizar();

        if($resultado['resultado'] == 1){
            echo json_encode([
                'mensaje' => 'Aspirante Eliminada de la Lista Correctamente',
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

//!Funcion Select Contingentes
public static function verDetallesAPI()
{
    $ing_id = $_GET['ing_id'];

    $sql = "SELECT
                cont_aspirantes.asp_catalogo,
                MAX(cont_aspirantes.asp_nom1) AS asp_nom1,
                MAX(cont_aspirantes.asp_nom2) AS asp_nom2,
                MAX(cont_aspirantes.asp_ape1) AS asp_ape1,
                MAX(cont_aspirantes.asp_ape2) AS asp_ape2,
                CASE
                    WHEN MAX(cont_aspirantes.asp_genero) = 'M' THEN 'MASCULINO'
                    WHEN MAX(cont_aspirantes.asp_genero) = 'F' THEN 'FEMENINO'
                    ELSE MAX(cont_aspirantes.asp_genero)
                END AS asp_genero,
                MAX(cont_aspirantes.asp_dpi) AS asp_dpi,
                MAX(GRA.gra_desc_md) AS asp_grado_desc,
                MAX(cont_puestos.pue_nombre) AS asp_puesto
            FROM
                cont_ingresos
            INNER JOIN cont_aspirantes ON cont_ingresos.ing_aspirante = cont_aspirantes.asp_id
            LEFT JOIN asig_grado_puesto ON cont_ingresos.ing_puesto = asig_grado_puesto.asig_puesto
            LEFT JOIN mper ON cont_aspirantes.asp_catalogo = mper.per_catalogo
            LEFT JOIN grados GRA ON mper.per_grado = GRA.gra_codigo
            LEFT JOIN cont_puestos ON cont_ingresos.ing_puesto = cont_puestos.pue_id
            WHERE
                cont_ingresos.ing_id = $ing_id
            GROUP BY
                cont_aspirantes.asp_catalogo;
";

    try {
        $detalles = Ingreso::fetchArray($sql);
        echo json_encode($detalles);
    } catch (Exception $e) {
        return [];
    }
}
}
