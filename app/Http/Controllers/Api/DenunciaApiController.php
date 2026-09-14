<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Denuncia;
use App\Models\DenunciaFoto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DenunciaApiController extends Controller
{
    public function index(): JsonResponse
    {
        $denuncias = Denuncia::with(['tipoDenuncia', 'fotos'])->latest()->get();

        return response()->json([
            'data' => $denuncias->map(fn (Denuncia $denuncia) => [
                'id' => $denuncia->id,
                'descripcion' => $denuncia->descripcion,
                'latitud' => (float) $denuncia->latitud,
                'longitud' => (float) $denuncia->longitud,
                'direccion' => $denuncia->direccion,
                'estado' => $denuncia->estado,
                'prioridad' => $denuncia->prioridad,
                'usuario_id' => $denuncia->usuario_id,
                'created_at' => $denuncia->created_at,
                'tipo_denuncia' => [
                    'id' => $denuncia->tipoDenuncia->id,
                    'nombre' => $denuncia->tipoDenuncia->nombre,
                ],
                'fotos_urls' => $denuncia->fotos->map->fotoUrl(),
            ]),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tipo_denuncia_id' => ['required', 'exists:tipos_denuncia,id'],
            'usuario_id' => ['nullable', 'exists:users,id'],
            'descripcion' => ['required', 'string'],
            'latitud' => ['required', 'numeric', 'between:-90,90'],
            'longitud' => ['required', 'numeric', 'between:-180,180'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'prioridad' => ['nullable', Rule::in(Denuncia::PRIORIDADES)],
            'fotos' => ['required', 'array', 'min:1'],
            'fotos.*' => ['image', 'max:8192'],
        ]);

        $denuncia = Denuncia::create([
            'tipo_denuncia_id' => $validated['tipo_denuncia_id'],
            'usuario_id' => $validated['usuario_id'] ?? null,
            'descripcion' => $validated['descripcion'],
            'latitud' => $validated['latitud'],
            'longitud' => $validated['longitud'],
            'direccion' => $validated['direccion'] ?? null,
            'prioridad' => $validated['prioridad'] ?? Denuncia::MODERADO,
            'estado' => Denuncia::PENDIENTE,
        ]);

        foreach ($request->file('fotos') as $foto) {
            $nombreFoto = Str::uuid().'.'.$foto->getClientOriginalExtension();
            $foto->move(public_path('imagenes_denuncias'), $nombreFoto);

            DenunciaFoto::create([
                'denuncia_id' => $denuncia->id,
                'foto' => $nombreFoto,
            ]);
        }

        $denuncia->load('fotos');

        return response()->json([
            'message' => 'Denuncia registrada correctamente.',
            'data' => [
                'id' => $denuncia->id,
                'estado' => $denuncia->estado,
                'prioridad' => $denuncia->prioridad,
                'fotos_urls' => $denuncia->fotos->map->fotoUrl(),
                'created_at' => $denuncia->created_at,
            ],
        ], 201);
    }
}
