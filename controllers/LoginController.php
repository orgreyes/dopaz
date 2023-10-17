<?php

namespace Controllers;

use Exception;
use MVC\Router;
use PDOException;
use Model\Permiso;

class LoginController {
    
    public static function index(Router $router){
        isNotAuth();
        $router->render('login/index', []);
    }

    public static function splash(Router $router){
        isNotAuth();
        $router->render('login/index', []);
    }
    public static function logout(Router $router){
        isAuth();
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }

    public static function login(){
        $usuario = $_POST['user'];
        $pasword = $_POST['password'];
        session_start();
        $_SESSION['auth_user'] = $usuario;
        $_SESSION['pass'] = $pasword;
        try {
            $db = conectarBD();
        } catch (PDOException $e) {
            $db = null;
        }


        if($db){
            setDB();
            try {
                
               $permisos = Permiso::fetchArray("SELECT menu_clave FROM niveles_autocom inner join menuautocom on aut_permiso = menu_codigo where aut_plaza = (select per_plaza from mper where per_catalogo = $usuario ) ");
            
            } catch (Exception $e) {
                echo json_encode($e);
                exit;
            }

            foreach ($permisos as $key => $permiso) {
                $_SESSION[$permiso['menu_clave']] = 1;
            }
            
            echo json_encode([
                "mensaje" => "Inicio exitoso",
                // "session" => $_SESSION,
                "estado" => session_status(),
                "codigo" => 1,
            ]);
        }else{
            
            echo json_encode([
                "mensaje" => "Credenciales incorrectas",
                "estado" => session_status(),
                "codigo" => 0,
            ]);
            $_SESSION = [];
            session_destroy();
        }
    }

}