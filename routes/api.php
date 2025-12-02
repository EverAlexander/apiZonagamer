<?php

use App\Http\Controllers\BrandsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\GameConsoleController;
use App\Http\Controllers\ModelProductController;
use App\Models\ModelProduct;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Crud de marca de productos
Route::get('brands/select',[BrandsController::class, 'select']);
Route::put('brands/update/{id_marca}',[BrandsController::class, 'update']);
Route::post('brands/store',[BrandsController::class, 'store']);
Route::delete('brands/delete/{id}',[BrandsController::class, 'delete']);
Route::get('brands/find/{id}',[BrandsController::class, 'find']);

//Crud de modelo de producto
Route::get('modelProduct/select',[ModelProductController::class, 'select']);
Route::post('modelProduct/store',[ModelProductController::class, 'store']);
Route::put('modelProduct/update/{id_marca}',[ModelProductController::class, 'update']);
Route::delete('modelProduct/delete/{id}',[ModelProductController::class, 'delete']);

//Crud de Consola de video juegos
Route::get('Consola/select',[GameConsoleController::class, 'select']);
Route::post('Consola/store',[GameConsoleController::class, 'store']);
Route::put('Consola/update/{id_marca}',[GameConsoleController::class, 'update']);
Route::delete('Consola/delete/{id}',[GameConsoleController::class, 'delete']);


//Obtener datos de ubicacion y estados
Route::get('Consola/selectUbicacion',[GameConsoleController::class, 'selectUbicacion']);
Route::get('Consola/selectEstados',[GameConsoleController::class, 'selectEstados']);
