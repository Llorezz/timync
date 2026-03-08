<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpToken extends Model
{
    protected $fillable = ['email', 'slug', 'codigo', 'usado', 'expires_at'];
    protected $casts = ['expires_at' => 'datetime', 'usado' => 'boolean'];
}
