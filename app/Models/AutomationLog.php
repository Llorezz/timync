<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationLog extends Model
{
    protected $fillable = [
        'automation_rule_id', 'user_id', 'cita_id', 'cliente_id',
        'canal', 'destinatario', 'mensaje', 'estado', 'error'
    ];

    public function rule()
    {
        return $this->belongsTo(AutomationRule::class, 'automation_rule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
