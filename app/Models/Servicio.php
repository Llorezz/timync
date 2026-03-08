<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'user_id', 'nombre', 'descripcion', 'foto',
        'descripcion_larga', 'empleado_id', 'precio',
        'duracion_minutos', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
