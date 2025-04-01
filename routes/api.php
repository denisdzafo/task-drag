<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
], function () {
    Route::group([
        'prefix' => 'task',
    ], function () {
        Route::get('get', [TaskController::class, 'getAllTasks']);
        Route::post('create', [TaskController::class, 'createTask']);
        Route::post('update/{id}', [TaskController::class, 'updateTask']);
        Route::post('delete/{id}', [TaskController::class, 'deleteTask']);
    });

    Route::group([
        'prefix' => 'project',
    ], function () {
        Route::get('get', [ProjectController::class, 'getAllProjects']);
        Route::post('create', [ProjectController::class, 'createProject']);
        Route::post('update/{id}', [ProjectController::class, 'updateProject']);
        Route::post('delete/{id}', [ProjectController::class, 'deleteProject']);
    });

});
