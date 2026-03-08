<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'user_id', 'nombre', 'tipo', 'asunto',
        'color_primario', 'color_boton', 'texto_boton', 'url_boton',
        'cuerpo', 'predefinida'
    ];

    protected $casts = [
        'predefinida' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function renderizar(array $variables = []): string
    {
        $cuerpo = $this->cuerpo;
        foreach ($variables as $clave => $valor) {
            $cuerpo = str_replace('{' . $clave . '}', $valor, $cuerpo);
        }
        return $cuerpo;
    }
}
