<?php

use App\Http\Controllers\auth\UserAuthController;
use App\Http\Controllers\catalogs\CategoryController;
use App\Http\Controllers\catalogs\MediaController;
use App\Http\Controllers\catalogs\ModuleController;
use App\Http\Controllers\catalogs\SolutionController;
use App\Http\Controllers\catalogs\StateController;
use App\Http\Controllers\login\LoginController;
use App\Http\Controllers\role\RoleController;
use App\Http\Controllers\tickets\AdminController;
use App\Http\Controllers\tickets\NotificationsController;
use App\Http\Controllers\tickets\SpecialistController;
use App\Http\Controllers\tickets\UserTicketController;
use App\Http\Controllers\user\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResources([
    '/api/login' => LoginController::class,
    '/api/usuarios' => UserController::class,
    '/api/roles' => RoleController::class,
    '/api/categorias' => CategoryController::class,
    '/api/medio' => MediaController::class,
    '/api/estados' => StateController::class,
    '/api/tickets' => UserTicketController::class,
    '/api/modulos' => ModuleController::class,
    '/api/soluciones' => SolutionController::class,
    '/api/asignaciones' => SpecialistController::class,
    '/api/notificaciones' => NotificationsController::class,
    '/api/usuario' => UserAuthController::class,
]);

Route::apiResource('/api/ticketasignados', AdminController::class)->parameters([
    'ticketasignados' => 'ticket',
]);

Route::get('/api/prioridad/{id}', [StateController::class, 'allPriorityState']);
Route::put('/api/reasignar/{reasign}', [SpecialistController::class, 'reasignTicket']);
Route::put('/api/asnotificacion/{id}', [NotificationsController::class, 'markAsRead']);
Route::put('/api/allnotificacion/{id}', [NotificationsController::class, 'markAllasRead']);
Route::put('/api/allnotificacion/{id}', [NotificationsController::class, 'markAllasRead']);
Route::put('/api/contrasena/{user}', [UserAuthController::class, 'editPassword']);

Route::put('/api/reactivar/{assign}', [AdminController::class, 'reactivate']);
Route::put('/api/finalizar/{assign}', [AdminController::class, 'finalize']);
Route::get('/api/conteo', [AdminController::class, 'count']);
Route::get('/api/monitoreo/{id}', [AdminController::class, 'monitoring']);
Route::get('/api/terminar/{id}', [AdminController::class, 'finished']);