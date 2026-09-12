<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('users')->orderBy('nombre')->get();

        return view('roles.index', compact('roles'));
    }

    public function edit(Role $role): View
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_if($role->esProtegido(), 403, 'No se pueden modificar los roles predefinidos.');

        $validated = $this->validateRequest($request, $role->id);

        $role->update($validated);

        return redirect()->route('roles.index')->with('status', 'Rol actualizado correctamente.');
    }

    public function destroy(Request $request, Role $role): RedirectResponse
    {
        abort_unless($request->user()->puedeEliminarODesactivar(), 403, 'No tenés permiso para eliminar roles.');
        abort_if($role->esProtegido(), 403, 'No se pueden eliminar los roles predefinidos.');

        if ($role->users()->exists()) {
            return redirect()->route('roles.index')->with('status', 'No se puede eliminar: hay usuarios con este rol asignado.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('status', 'Rol eliminado correctamente.');
    }

    private function validateRequest(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:roles,nombre,'.$ignoreId],
            'descripcion' => ['nullable', 'string'],
        ]);
    }
}
