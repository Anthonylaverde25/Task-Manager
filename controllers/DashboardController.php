<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController {

    public static function index (Router $router) {
        session_start();
        isAuth();

        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos

        ]);
    }

    public static function crear_proyecto (Router $router){
        $alertas = [];
        session_start();
        isAuth();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){

                $hash = md5(uniqid());
                $proyecto->url = $hash;
                $proyecto->propietarioId = $_SESSION['id'];
                $proyecto->guardar();
                header('Location: /proyecto?id='. $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            "titulo" => "Crear proyecto",
            "alertas" => $alertas
        ]);

    }


    public static function proyecto (Router $router) {
        session_start();
        isAuth();
        $token = $_GET['id'];

        if(!$token) header('Location: /dashboard');
        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']) header('Location: /dashboard');

        //debuguear($proyecto);

        $titulo = $proyecto->proyecto;
         

        $router->render('dashboard/proyecto', [
            'titulo' => $titulo

        ]);
    }

    public static function perfil (Router $router){
        $alertas = [];
        session_start();
        isAuth();

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();

            $existe_usuario = Usuario::where('email', $usuario->email);

            if(empty($alertas)){

                if($existe_usuario && $existe_usuario->id !== $usuario->id){
                    Usuario::setAlerta('error', 'Email no valido, ya pertenece a otro usuario');
                    $alertas = $usuario->getAlertas();
                }else{
                    $usuario->guardar();

                    $_SESSION['nombre'] = $usuario->nombre;
                    Usuario::setAlerta('exito','Usuario actualizado correctamente');
                    $alertas = $usuario->getAlertas();
                    //debuguear($usuario);
                }
                
        

            }
        }

        $router->render('dashboard/perfil' , [
            'titulo' => "Perfil",
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }

    public static function cambiar_password (Router $router){
        $alertas = [];

        session_start();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar_password();

            if(empty($alertas)){

                $resultado = $usuario->comprobar_password();

                if($resultado){
                    $usuario->password = $usuario->password_nuevo;
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    $usuario->hashPassword();
                    $respuesta = $usuario->guardar();
                    if($respuesta){
                        Usuario::setAlerta('exito','Contraseña guardada correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                }

            }else{
                Usuario::setAlerta('error','Contraseña incorrecta');
                $alertas = $usuario->getAlertas();
            }
        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar password',
            'alertas' => $alertas,
        ]);
    }
}