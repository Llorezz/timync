<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['user_id', 'nombre', 'email', 'telefono', 'notas', 'avatar', 'provider', 'politica_aceptada', 'politica_aceptada_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
