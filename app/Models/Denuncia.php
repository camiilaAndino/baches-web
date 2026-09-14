<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Denuncia extends Model
{
    public const PENDIENTE = 'pendiente';

    public const EN_PROCESO = 'en_proceso';

    public const RESUELTA = 'resuelta';

    /**
     * @var array<int, string>
     */
    public const ESTADOS = [
        self::PENDIENTE,
        self::EN_PROCESO,
        self::RESUELTA,
    ];

    public const LEVE = 'leve';

    public const MODERADO = 'moderado';

    public const GRAVE = 'grave';

    /**
     * @var array<int, string>
     */
    public const PRIORIDADES = [
        self::LEVE,
        self::MODERADO,
        self::GRAVE,
    ];

    protected $fillable = [
        'tipo_denuncia_id',
        'usuario_id',
        'descripcion',
        'latitud',
        'longitud',
        'direccion',
        'estado',
        'prioridad',
    ];

    protected function casts(): array
    {
        return [
            'latitud' => 'decimal:7',
            'longitud' => 'decimal:7',
        ];
    }

    public function tipoDenuncia(): BelongsTo
    {
        return $this->belongsTo(TipoDenuncia::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(DenunciaFoto::class);
    }
}
