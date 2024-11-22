<?php

use App\Http\Controllers\controllerEstudiante;
use App\Http\Controllers\controllerNota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("app")->group(function(){
    Route::get('/estudiante', [controllerEstudiante::class, 'index']);

    Route::get('/estudiante/{cod}', [controllerEstudiante::class, 'show']);
    
    Route::post('/estudiante', [controllerEstudiante::class, 'store']);
    
    Route::put('/estudiante/{cod}', [controllerEstudiante::class, 'update']);
    
    Route::delete('/estudiante/{cod}', [controllerEstudiante::class, 'destroy']);
    
    Route::get('/nota', [controllerNota::class, 'index']);
    
    Route::post('/nota', [controllerNota::class, 'store']);
});