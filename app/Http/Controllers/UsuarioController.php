<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(): View
    {
        $users = User::with('role')->latest()->get();

        return view('usuarios.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::where('nombre', '!=', Role::USUARIO_APP)->orderBy('nombre')->get();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['nullable', Rule::exists('roles', 'id')->where(fn ($query) => $query->where('nombre', '!=', Role::USUARIO_APP))],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'] ?? null,
            'activo' => true,
        ]);

        return redirect()->route('usuarios.index')->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        $roles = Role::where('nombre', '!=', Role::USUARIO_APP)->orderBy('nombre')->get();

        return view('usuarios.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['nullable', Rule::exists('roles', 'id')->where(fn ($query) => $query->where('nombre', '!=', Role::USUARIO_APP))],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('usuarios.index')->with('status', 'Usuario actualizado correctamente.');
    }

    public function toggleEstado(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()->puedeEliminarODesactivar(), 403, 'No tenés permiso para activar o desactivar usuarios.');

        if ($user->id === $request->user()->id) {
            return redirect()->route('usuarios.index')->with('status', 'No podés desactivar tu propio usuario.');
        }

        $user->update(['activo' => ! $user->activo]);

        $mensaje = $user->activo ? 'Usuario activado correctamente.' : 'Usuario desactivado correctamente.';

        return redirect()->route('usuarios.index')->with('status', $mensaje);
    }
}
