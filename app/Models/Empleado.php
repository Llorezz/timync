<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = [
        'user_id', 'nombre', 'email', 'telefono', 'especialidad', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function horarios()
    {
        return $this->hasMany(EmpleadoHorario::class)->orderBy('dia_semana');
    }

    public function diasLibres()
    {
        return $this->hasMany(EmpleadoDiaLibre::class)->orderBy('fecha_inicio');
    }
}
