<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\TareasController;
use MVC\Router;
$router = new Router();

// LOGIN
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);



// CREAR CUENTA
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);



// FORMULARIO DE OLVIDE MI PASSWORD
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);


// RESTABLECER
$router->get('/restablecer', [LoginController::class, 'restablecer']);
$router->post('/restablecer', [LoginController::class, 'restablecer']);


// MENSAJE
$router->get('/mensaje', [LoginController::class, 'mensaje']);


// CONFIRMAR
$router->get('/confirmar', [LoginController::class, 'confirmar']);


// ZONA DE LOS PROYECTOS
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);

$router->get('/proyecto', [DashboardController::class, 'proyecto']);
$router->get('/cambiar-password', [DashboardController::class, 'cambiar_password']);
$router->post('/cambiar-password', [DashboardController::class, 'cambiar_password']);



// API PARA LAS TAREAS
$router->get('/api/tareas', [TareasController::class,'index']);
$router->post('/api/tarea', [TareasController::class,'crear']);
$router->post('/api/tarea/actualizar', [TareasController::class,'actualizar']);
$router->post('/api/tarea/eliminar', [TareasController::class,'eliminar']);



//PERFIL    
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);



// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();