<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tareas;

class TareasController {

    public static function index (){


        $proyectoId = $_GET['id'];
        if(!$proyectoId) header('Location: /dashboard');
        $proyecto = Proyecto::where('url', $proyectoId);

        session_start();
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('Location: /404');
        $tareas = Tareas::belongsTo('proyectoId', $proyecto->id);

        $respuesta = ['tareas' => $tareas];
        
        echo json_encode($respuesta);

        //debuguear($respuesta);
    }

    public static function crear (){

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();

            $proyectoId = $_POST['proyectoId'];
            $proyecto = Proyecto::where('url', $proyectoId);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un error al agregar la tarea',
                    'proyectoId' => $proyecto->id
                ];

                echo json_encode($respuesta);
                return;

            }

            $tarea = new Tareas($_POST);
            $tarea->proyectoId = $proyecto->id;

            $resultado = $tarea->guardar();

            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea agregada correctamente'
            ];
            
            echo json_encode($respuesta);

            
            
        
          
        }
    }

    public static function actualizar (){
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $proyecto = Proyecto::where('url', $_POST['proyectoId'] );
            
            session_start();
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){

                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Error al actualizar tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tareas($_POST);
            $tarea->proyectoId = $proyecto->id;

            $resultado = $tarea->guardar();

            if($resultado){
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' => 'Tarea actualizada correctamente'

                ];

                echo json_encode(['respuesta' => $respuesta]);
            }


        }
    }

    public static function eliminar (){
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            session_start();

            if(!$proyecto || $proyecto->propietarioId!== $_SESSION['id']){

                $respuesta = [
                    'tipo' => 'error',
                    'tipo' => 'Error eliminando tarea'
                ];

                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tareas($_POST);
            $resultado = $tarea->eliminar();
            $resultado = [
                'resultado' => $resultado,
                'tipo' => 'exito',
                'mensaje' => 'Tarea eliminada correctamenet'
            ];

            echo json_encode($resultado);
        }
    }
}