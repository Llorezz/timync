<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioNegocio extends Model
{
    protected $table = 'horarios_negocio';

    protected $fillable = [
        'user_id', 'dia_semana', 'activo', 'hora_apertura', 'hora_cierre'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    const DIAS = [
        0 => 'Lunes',
        1 => 'Martes',
        2 => 'Miércoles',
        3 => 'Jueves',
        4 => 'Viernes',
        5 => 'Sábado',
        6 => 'Domingo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
