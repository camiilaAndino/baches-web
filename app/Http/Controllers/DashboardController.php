<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\TipoDenuncia;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsuarios = User::count();
        $usuariosActivos = User::where('activo', true)->count();
        $usuariosInactivos = $totalUsuarios - $usuariosActivos;
        $totalRoles = Role::count();
        $totalTiposDenuncia = TipoDenuncia::count();

        $ultimosUsuarios = User::with('role')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalUsuarios',
            'usuariosActivos',
            'usuariosInactivos',
            'totalRoles',
            'totalTiposDenuncia',
            'ultimosUsuarios',
        ));
    }
}
