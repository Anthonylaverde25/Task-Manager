<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;



class loginController {

    public static function login (Router $router ){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
           $usuario = new Usuario($_POST);
           $alertas = $usuario->validarLogin();

           if(empty($alertas)){
            $usuario = Usuario::where('email', $usuario->email);


            if(!$usuario || !$usuario->confirmado){
                Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
            }else {
                if(password_verify($_POST['password'], $usuario->password)){
                    session_start();
                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    header("Location: /dashboard");
                }else{
                    debuguear("incorrecto");
                }

                
            }
           }
        };

        $alertas = Usuario::getAlertas();

        $router->render('auth/Login', [
            'titulo' => 'Login',
            'alertas' => $alertas
        ]);
        
       

        
       
        
    }

    

    public static function logout () {
        session_start();
        $_SESSION = [];
        header("location: /");

        if($_SERVER['REQUEST_METHOD'] ==='POST'){

        }
    }

    public static function crear (Router $router) {

        $alertas = [];
        $usuario = new Usuario;

        if($_SERVER['REQUEST_METHOD'] === "POST"){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario){
                    Usuario::setAlerta('error', 'El usuario ya se encuentra registrado');
                    $alertas = Usuario::getAlertas();
                }else {

                    // Hashear el password
                    $usuario->hashPassword();


                    // Eliminar el password2
                    unset($usuario->password2);


                    // Crear token de validacion
                    $usuario->crearToken();


                    // Enviar mail para validar
                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
                    $email->enviarConfirmacion();

                    // crear un nuevo usuario
                    $resultado = $usuario->guardar();

                    if($resultado){
                        header("location: /mensaje");
                    }

                }
            }

           
        };

        $router->render('auth/Crear', [
            'titulo' => "Crear cuenta",
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }


    
    public static function olvide (Router $router) {
        
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
          $usuario = new Usuario($_POST);
          $alertas = $usuario->validarEmail();


          if(empty($alertas)){
            $usuario = Usuario::where('email', $usuario->email);

            if($usuario && $usuario->confirmado){
                //GENERA UN NUEVO TOKEN
                $usuario->crearToken();
                unset($usuario->password2);

                //ACTUALIZA EL USUARIO
                $usuario->guardar();

                //ENVIAR EMAIL
                $mail = new Email($usuario->email,$usuario->nombre,$usuario->token);
                $mail->enviarInstrucciones();

                //IMPRIMIR ALERTA
                Usuario::setAlerta('exito', 'Hemos enviado las intrucciones a tu email');

            }else{
                Usuario::setAlerta('error', 'El usuario no existe o no esta verificado');
            };

            }
        
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/Olvide', [
            'titulo' => 'Recuperar cuenta',
            'alertas' => $alertas
        ]);


    }

    public static function restablecer (Router $router) {

        $token = s($_GET["token"]);
        $mostrar = true;



        if(!$token) return header("location: /");

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'token no valido');
            $mostrar = false;
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();
            

            if(empty($alertas)){

                //Hashear password
                $usuario->password = password_hash($usuario->password, PASSWORD_BCRYPT);
                unset($usuario->password2);

                //eliminar token
                $usuario->token = null;

                // guardar nuevo usuario
                $resultado = $usuario->guardar();

                // redericionar
                if($resultado){
                    header("location: /");

                }

            }
           
        };

        $alertas = Usuario::getAlertas();

        $router->render('auth/Restablecer', [
            'titulo' => 'Restablecer contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar

        ]);
        
    }

    public static function mensaje (Router $router) {

        $router->render('auth/Mensaje', [
            'titulo' => 'Mensaje'
        ]);

    }

    public static function confirmar (Router $router) {

        $token = s($_GET['token']);

        if(!$token) header("location: /");
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
        }else{
            unset($usuario->password2);
            $usuario->token = null;
            $usuario->confirmado = 1;



            $usuario->guardar();

            Usuario::setAlerta('exito', 'Usuario confirmado correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/Confirmar', [
            'titulo' => 'Confirmar tu cuenta',
            'alertas' => $alertas

        ]);
    }


}
