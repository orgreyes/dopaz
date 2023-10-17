<?php

namespace Controllers;

use Exception;
use Model\Usuario;
use MVC\Router;

class MenuController {
    public static function index(Router $router){
        isAuth();
        try {
            $usuario = Usuario::fetchFirst("SELECT per_catalogo, trim(per_nom1) || ' ' || trim(per_nom2) || ' ' ||trim(per_ape1) || ' ' || trim(per_ape1) as nombre , dep_desc_lg as dependencia, gra_desc_md as grado from mper inner join morg on per_plaza = org_plaza inner join mdep on org_dependencia = dep_llave inner join grados on per_grado = gra_codigo where per_catalogo = user ");
            
        } catch (Exception $e) {
            getHeadersApi();
            echo json_encode([
                "detalle" => $e->getMessage(),       
                "mensaje" => "Error de conexión bd",
        
                "codigo" => 5,
            ]);
            exit;
        }
        $router->render('menu/index', [
            'usuario' => $usuario,
        ]);
    }

}