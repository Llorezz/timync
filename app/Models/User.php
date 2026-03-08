<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'rol',
        'nombre_negocio', 'telefono_negocio', 'activo',
        'slug', 'foto_portada', 'descripcion_negocio',
        'direccion', 'ciudad', 'horario_apertura', 'horario_cierre',
        'instagram', 'facebook', 'whatsapp_negocio',
        'fotos_galeria', 'color_primario'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'activo'            => 'boolean',
        'fotos_galeria'     => 'array',
    ];

public function sendPasswordResetNotification($token): void
{
    $this->notify(new ResetPasswordNotification($token));
}

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function isNegocio(): bool
    {
        return $this->rol === 'negocio';
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
public function horariosNegocio()
{
    return $this->hasMany(HorarioNegocio::class);
}

    public function getUrlReservaAttribute(): string
    {
return url('/reserva/' . $this->slug);
    }

    public static function generarSlug(string $nombre): string
    {
        $slug  = Str::slug($nombre);
        $count = User::where('slug', 'like', $slug . '%')->count();
        return $count ? $slug . '-' . ($count + 1) : $slug;
    }
}
