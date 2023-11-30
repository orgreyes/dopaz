<?php

namespace Controllers;

use Exception;
use Model\Aprobado;
use Model\Ingreso;
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

  //!Funcion Buscar
  public static function buscarPorContingenteAPI()
  {
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
                    cont_aprovados ca
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

}
