<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DenunciaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TipoDenunciaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/denuncias', [DenunciaController::class, 'index'])->name('denuncias.index');
    Route::get('/denuncias/{denuncia}', [DenunciaController::class, 'show'])->name('denuncias.show');
    Route::patch('/denuncias/{denuncia}/estado', [DenunciaController::class, 'actualizarEstado'])->name('denuncias.estado');

    Route::patch('/usuarios/{user}/estado', [UsuarioController::class, 'toggleEstado'])->name('usuarios.estado');
    Route::resource('usuarios', UsuarioController::class)
        ->parameters(['usuarios' => 'user'])
        ->except(['show', 'destroy']);

    Route::resource('tipos-denuncia', TipoDenunciaController::class)
        ->parameters(['tipos-denuncia' => 'tipoDenuncia'])
        ->except(['show']);

    Route::resource('roles', RoleController::class)->except(['show', 'create', 'store']);
});

require __DIR__.'/auth.php';
