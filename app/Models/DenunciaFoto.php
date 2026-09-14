<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DenunciaFoto extends Model
{
    protected $fillable = [
        'denuncia_id',
        'foto',
    ];

    public function denuncia(): BelongsTo
    {
        return $this->belongsTo(Denuncia::class);
    }

    public function fotoUrl(): string
    {
        return asset('imagenes_denuncias/'.$this->foto);
    }
}
