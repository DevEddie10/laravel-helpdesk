<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewController;
use App\Http\Controllers\role\RoleController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\login\LoginController;
use App\Http\Controllers\catalogs\MediaController;
use App\Http\Controllers\catalogs\StateController;
use App\Http\Controllers\catalogs\ModuleController;
use App\Http\Controllers\tickets\AssignedController;
use App\Http\Controllers\catalogs\CategoryController;
use App\Http\Controllers\catalogs\SolutionController;
use App\Http\Controllers\tickets\SpecialistController;
use App\Http\Controllers\tickets\NotificationsController;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResources([
    '/api/login'      => LoginController::class,
    '/api/usuarios'   => UserController::class,
    '/api/roles'      => RoleController::class,
    '/api/categorias' => CategoryController::class,
    '/api/medio'      => MediaController::class,
    '/api/estados'    => StateController::class,
    '/api/tickets'    => AssignedController::class,
    '/api/modulos'    => ModuleController::class,
    '/api/soluciones' => SolutionController::class,
    '/api/asignaciones' => SpecialistController::class,
    '/api/notificaciones' => NotificationsController::class,
]);

Route::get('/api/prioridad/{id}', [StateController::class, 'allPriorityState']);
Route::put('/api/reasignar/{id}', [SpecialistController::class, 'reasignTicket']);
Route::get('/api/monitoreo/{id}', [AssignedController::class, 'monitoringTicket']);
Route::get('/api/terminar/{id}', [AssignedController::class, 'endupTicket']);
Route::put('/api/reactivar/{assign}', [AssignedController::class, 'reactivateTicket']);
Route::put('/api/finalizado/{assign}', [AssignedController::class, 'finishedTicket']);

Route::put('/api/asnotificacion/{id}', [NotificationsController::class, 'markAsRead']);
Route::put('/api/allnotificacion/{id}', [NotificationsController::class, 'markAllasRead']);