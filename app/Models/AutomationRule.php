<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationRule extends Model
{
    protected $fillable = [
        'user_id', 'tipo', 'nombre', 'config', 'canal', 'activo', 'ultima_ejecucion'
    ];

    protected $casts = [
        'config'           => 'array',
        'activo'           => 'boolean',
        'ultima_ejecucion' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(AutomationLog::class);
    }
}
