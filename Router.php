<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];   // Almacena rutas GET y sus funciones controladoras
    public array $postRoutes = [];  // Almacena rutas POST y sus funciones controladoras
    



    public function get($url, $fn)
    {
        // Asocia una ruta GET con una función controladora y la almacena en $getRoutes
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        // Asocia una ruta POST con una función controladora y la almacena en $postRoutes
        $this->postRoutes[$url] = $fn;
    }

  

    public function comprobarRutas()
    {
        // Obtiene la URL actual y el método de solicitud HTTP (GET o POST)
        $currentUrl = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }


        if ( $fn ) {
            // Call user fn va a llamar una función cuando no sabemos cual sera
            call_user_func($fn, $this); // This es para pasar argumentos
        } else {
            echo "Página No Encontrada o Ruta no válida";
        }
    }

    public function render($view, $datos = [])
    {

        // Leer lo que le pasamos  a la vista
        foreach ($datos as $key => $value) {
            $$key = $value;  // Doble signo de dolar significa: variable variable, básicamente nuestra variable sigue siendo la original, pero al asignarla a otra no la reescribe, mantiene su valor, de esta forma el nombre de la variable se asigna dinamicamente
        }

        ob_start(); // Almacenamiento en memoria durante un momento...

        // entonces incluimos la vista en el layout
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); // Limpia el Buffer
        include_once __DIR__ . '/views/layout.php';
    }
}









