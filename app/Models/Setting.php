<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'user_id',
        'mail_host', 'mail_port', 'mail_username', 'mail_password',
        'mail_from_address', 'mail_from_name',
        'telegram_bot_token', 'telegram_chat_id',
        'whatsapp_token', 'whatsapp_phone_id',
        'negocio_nombre', 'negocio_telefono', 'negocio_email', 'negocio_direccion',
    ];

    protected $hidden = [
        'mail_password', 'telegram_bot_token', 'whatsapp_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
