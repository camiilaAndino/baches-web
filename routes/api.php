<?php

use App\Http\Controllers\Api\DenunciaApiController;
use App\Http\Controllers\Api\TipoDenunciaApiController;
use App\Http\Controllers\Api\UsuarioApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UsuarioApiController::class, 'login']);
Route::post('/registro', [UsuarioApiController::class, 'registro']);

Route::get('/tipos-denuncia', [TipoDenunciaApiController::class, 'index']);
Route::get('/denuncias', [DenunciaApiController::class, 'index']);
Route::post('/denuncias', [DenunciaApiController::class, 'store']);
