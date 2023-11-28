<?php

namespace Controllers;

use Exception;
use Model\Ingreso;
use MVC\Router;

class EstadisticaController {
    public static function index(Router $router){

        $estadisticas = Ingreso::all();

        $router->render('estadisticas/index', []);
    }

        // //!Funcion Detalle
        public static function detalleEstadosAPI()
        {
    
            $sql = "SELECT
                    ing_aspirante,
                    CASE
                        WHEN ing_situacion = 1 THEN 'Solicitante'
                        WHEN ing_situacion = 2 THEN 'CalificarNotas'
                        WHEN ing_situacion = 3 THEN 'RevisionRequisitos'
                    END AS estado
                    FROM cont_ingresos";
            

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


