<?php
namespace App\Services;
use App\Models\Setting;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class NotificacionService
{
    public static function enviar(Setting $settings, string $canal, string $destinatario, string $mensaje, string $asunto = 'Notificación'): void
    {
        match($canal) {
            'email'    => self::enviarEmail($settings, $destinatario, $mensaje, $asunto),
            'telegram' => self::enviarTelegram($settings, $destinatario, $mensaje),
            'whatsapp' => self::enviarWhatsapp($settings, $destinatario, $mensaje),
            default    => null,
        };
    }

    public static function enviarEmail(Setting $settings, string $to, string $mensaje, string $asunto, bool $esHtml = false): void
    {
        config([
            'mail.mailers.sendmail.transport' => 'sendmail',
            'mail.default'                    => 'sendmail',
            'mail.from.address'               => $settings->mail_from_address ?? 'info@timync.com',
            'mail.from.name'                  => $settings->mail_from_name ?? 'Timync',
        ]);

        if ($esHtml) {
            Mail::html($mensaje, function (Message $msg) use ($to, $asunto) {
                $msg->to($to)->subject($asunto);
            });
        } else {
            Mail::raw($mensaje, function (Message $msg) use ($to, $asunto) {
                $msg->to($to)->subject($asunto);
            });
        }
    }

    public static function enviarTelegram(Setting $settings, string $chatId, string $mensaje): void
    {
        $url = "https://api.telegram.org/bot{$settings->telegram_bot_token}/sendMessage";
        file_get_contents($url . '?' . http_build_query([
            'chat_id' => $chatId,
            'text'    => $mensaje,
        ]));
    }

    public static function enviarWhatsapp(Setting $settings, string $telefono, string $mensaje): void
    {
        $url  = "https://graph.facebook.com/v18.0/{$settings->whatsapp_phone_id}/messages";
        $data = json_encode([
            'messaging_product' => 'whatsapp',
            'to'                => $telefono,
            'type'              => 'text',
            'text'              => ['body' => $mensaje],
        ]);
        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Authorization: Bearer {$settings->whatsapp_token}\r\nContent-Type: application/json\r\n",
                'content' => $data,
            ]
        ]);
        file_get_contents($url, false, $context);
    }
}
