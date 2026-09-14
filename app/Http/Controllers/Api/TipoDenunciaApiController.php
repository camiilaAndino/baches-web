<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoDenuncia;
use Illuminate\Http\JsonResponse;

class TipoDenunciaApiController extends Controller
{
    public function index(): JsonResponse
    {
        $tipos = TipoDenuncia::orderBy('nombre')->get(['id', 'nombre', 'descripcion']);

        return response()->json(['data' => $tipos]);
    }
}
