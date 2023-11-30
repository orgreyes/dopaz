<?php

namespace Controllers;

use Exception;
use Model\Ingreso;
use Model\Contingente;
use MVC\Router;

class EstadisticaController {
    public static function index(Router $router){

        $contingentes = static::buscaContingentes();
        $estadisticas = Ingreso::all();

        $router->render('estadisticas/index', [
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
//!Funcion Detalle
public static function detalleEstadosAPI(){
    
            $cont_id = $_GET['cont_id'];

            $sql = "SELECT
                        ci.ing_aspirante,
                        CASE
                            WHEN ci.ing_situacion = 1 THEN 'Solicitante'
                            WHEN ci.ing_situacion = 2 THEN 'CalificarNotas'
                            WHEN ci.ing_situacion = 3 THEN 'RevisionRequisitos'
                            WHEN ci.ing_situacion = 4 THEN 'Aprobados'
                        END AS estado
                    FROM cont_ingresos ci
                    JOIN (
                        SELECT
                            ing_aspirante,
                            MAX(ing_situacion) AS max_situacion
                        FROM cont_ingresos
                        WHERE ing_contingente = $cont_id  
                        GROUP BY ing_aspirante
                    ) max_sit ON ci.ing_aspirante = max_sit.ing_aspirante
                            AND ci.ing_situacion = max_sit.max_situacion;";
            

            try {
    
                $estados = Ingreso::fetchArray($sql);
    
                echo json_encode($estados);
            } catch (Exception $e) {
                echo json_encode([
                    'detalle' => $e->getMessage(),
                    'mensaje' => 'Ocurrió un error',
                    'codigo' => 0
                ]);
            }
}

}


