<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'user_id', 'cliente_id', 'empleado_id', 'servicio_id',
        'fecha_hora', 'fecha_fin', 'estado', 'notas'
    ];

    protected static function booted(): void
    {
        static::creating(function ($cita) {
            $servicio = Servicio::find($cita->servicio_id);
            if ($servicio) {
                $cita->fecha_fin = date('Y-m-d H:i:s',
                    strtotime($cita->fecha_hora) + ($servicio->duracion_minutos * 60)
                );
            }
        });

        static::updating(function ($cita) {
            $servicio = Servicio::find($cita->servicio_id);
            if ($servicio) {
                $cita->fecha_fin = date('Y-m-d H:i:s',
                    strtotime($cita->fecha_hora) + ($servicio->duracion_minutos * 60)
                );
            }
        });
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
