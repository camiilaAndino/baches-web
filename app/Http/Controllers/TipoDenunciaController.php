<?php

namespace App\Http\Controllers;

use App\Models\TipoDenuncia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TipoDenunciaController extends Controller
{
    public function index(): View
    {
        $tiposDenuncia = TipoDenuncia::orderBy('nombre')->paginate(15);

        return view('tipos-denuncia.index', compact('tiposDenuncia'));
    }

    public function create(): View
    {
        return view('tipos-denuncia.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        TipoDenuncia::create($validated);

        return redirect()->route('tipos-denuncia.index')->with('status', 'Tipo de denuncia creado correctamente.');
    }

    public function edit(TipoDenuncia $tipoDenuncia): View
    {
        return view('tipos-denuncia.edit', compact('tipoDenuncia'));
    }

    public function update(Request $request, TipoDenuncia $tipoDenuncia): RedirectResponse
    {
        $validated = $this->validateRequest($request, $tipoDenuncia->id);

        $tipoDenuncia->update($validated);

        return redirect()->route('tipos-denuncia.index')->with('status', 'Tipo de denuncia actualizado correctamente.');
    }

    public function destroy(TipoDenuncia $tipoDenuncia): RedirectResponse
    {
        $tipoDenuncia->delete();

        return redirect()->route('tipos-denuncia.index')->with('status', 'Tipo de denuncia eliminado correctamente.');
    }

    private function validateRequest(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:tipos_denuncia,nombre,'.$ignoreId],
            'descripcion' => ['nullable', 'string'],
        ]);
    }
}
