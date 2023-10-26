<?php

namespace Controllers;

use MVC\Router;
use Model\Destino;
use Exception;

class DestinoController {
    public static function index(Router $router){
        $router->render('destinos/index', []);
    }


    public static function buscarAPI(){
        $dest_nombre = $_GET['dest_nombre'] ?? '';
        $sql = "SELECT * FROM cont_destinos WHERE dest_situacion = '1'";
    
        if (!empty($dest_nombre)) {
            $dest_nombre = strtolower($dest_nombre);
            $sql .= " AND LOWER(dest_nombre) LIKE '%$dest_nombre%' ";
        }
    
        try {
            $destinos = Destino::fetchArray($sql); 
            echo json_encode($destinos);
        } catch (Exception $e) {
            echo json_encode([
                'detalle' => $e->getMessage(),
                'mensaje' => 'Ocurrió un error',
                'codigo' => 0
            ]);
        }
    }

}