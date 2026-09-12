<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nombre', 'descripcion'])]
class Role extends Model
{
    public const SUPER_ADMINISTRADOR = 'Super Administrador';

    public const ADMINISTRADOR = 'Administrador';

    public const USUARIO_APP = 'Usuario App';

    /**
     * Roles predefinidos que no se pueden eliminar ni renombrar desde el CRUD.
     * El catálogo de roles es fijo: no se crean roles nuevos.
     *
     * @var array<int, string>
     */
    public const ROLES_PROTEGIDOS = [
        self::SUPER_ADMINISTRADOR,
        self::ADMINISTRADOR,
        self::USUARIO_APP,
    ];

    public function esProtegido(): bool
    {
        return in_array($this->nombre, self::ROLES_PROTEGIDOS, true);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
