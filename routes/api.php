<?php

Route::apiResource('clientes', App\Http\Controllers\ClienteApiController::class);
/* Route::get('documentos/{id}/cliente', App\Http\Controllers\DocumentoApiController::class);*/ 
Route::apiResource('documentos', App\Http\Controllers\DocumentoApiController::class); 
