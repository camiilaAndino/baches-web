<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'activo', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function esSuperAdministrador(): bool
    {
        return $this->role?->nombre === Role::SUPER_ADMINISTRADOR;
    }

    public function esAdministrador(): bool
    {
        return $this->role?->nombre === Role::ADMINISTRADOR;
    }

    /**
     * El rol Administrador puede hacer todo excepto borrar o desactivar.
     * Super Administrador puede hacer todo. Un usuario sin rol asignado
     * no queda restringido por este permiso.
     */
    public function puedeEliminarODesactivar(): bool
    {
        return ! $this->esAdministrador();
    }
}
