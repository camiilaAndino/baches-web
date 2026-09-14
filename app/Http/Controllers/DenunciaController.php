<?php

namespace App\Http\Controllers;

use App\Models\Denuncia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DenunciaController extends Controller
{
    public function index(): View
    {
        $denuncias = Denuncia::with(['tipoDenuncia', 'fotos'])->latest()->get();

        return view('denuncias.index', compact('denuncias'));
    }

    public function show(Denuncia $denuncia): View
    {
        $denuncia->load(['tipoDenuncia', 'usuario', 'fotos']);

        return view('denuncias.show', compact('denuncia'));
    }

    public function actualizarEstado(Request $request, Denuncia $denuncia): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => ['required', Rule::in(Denuncia::ESTADOS)],
        ]);

        $denuncia->update(['estado' => $validated['estado']]);

        return redirect()->route('denuncias.show', $denuncia)->with('status', 'Estado actualizado correctamente.');
    }
}
