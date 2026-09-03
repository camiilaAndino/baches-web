<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsuarioController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);

        return view('usuarios.index', compact('users'));
    }
}
