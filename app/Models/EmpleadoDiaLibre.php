<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpleadoDiaLibre extends Model
{
    protected $table = 'empleado_dias_libres';

    protected $fillable = [
        'empleado_id', 'fecha_inicio', 'fecha_fin', 'motivo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
