<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoHorario extends Model
{
    protected $fillable = [
        'empleado_id', 'dia_semana', 'activo',
        'hora_inicio_manana', 'hora_fin_manana',
        'hora_inicio_tarde', 'hora_fin_tarde',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    const DIAS = [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function getNombreDiaAttribute(): string
    {
        return self::DIAS[$this->dia_semana] ?? '';
    }
}
