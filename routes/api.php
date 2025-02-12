<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ItemListController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('user', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);

    //checklist
    Route::get('checklist', [ChecklistController::class, 'list']);
    Route::get('checklist/{id}', [ChecklistController::class, 'show']);
    Route::delete('checklist/{id}', [ChecklistController::class, 'delete']);
    Route::post('checklist/{id}', [ChecklistController::class, 'update']);
    Route::post('checklist', [ChecklistController::class, 'create']);

    //item list
    Route::get('item-list/{id}', [ItemListController::class, 'show']);
    Route::delete('item-list/{id}', [ItemListController::class, 'delete']);
    Route::post('item-list/{id}', [ItemListController::class, 'update']);
    Route::post('item-list', [ItemListController::class, 'create']);
});
