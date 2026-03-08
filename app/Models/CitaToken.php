<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitaToken extends Model
{
    protected $fillable = ['cita_id', 'token', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }
}
