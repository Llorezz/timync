<?php

namespace App\Jobs;

use App\Models\AutomationLog;
use App\Models\AutomationRule;
use App\Models\Cita;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class ProcesarRecordatoriosCitas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $reglas = AutomationRule::where('tipo', 'recordatorio')
            ->where('activo', true)
            ->get();

        foreach ($reglas as $regla) {
            $settings   = Setting::where('user_id', $regla->user_id)->first();
            $horasAntes = (int) ($regla->config['horas_antes'] ?? 24);

            $citas = Cita::with(['cliente', 'empleado', 'servicio'])
                ->where('user_id', $regla->user_id)
                ->where('estado', 'confirmada')
                ->whereBetween('fecha_hora', [
                    now()->addHours($horasAntes - 3),
                    now()->addHours($horasAntes + 3)
                ])
                ->get();

            foreach ($citas as $cita) {
                if (!$cita->cliente) continue;

                $mensaje      = $this->construirMensaje($regla, $cita);
                $destinatario = $this->getDestinatario($regla->canal, $cita->cliente);
                $estado       = 'pendiente';
                $error        = null;

                try {
                    if ($regla->canal === 'email' && $settings?->mail_host) {
                        $this->enviarEmail($settings, $destinatario, $mensaje, $settings->negocio_nombre ?? 'Timync');
                        $estado = 'enviado';
                    } elseif ($regla->canal === 'telegram' && $settings?->telegram_bot_token) {
                        $this->enviarTelegram($settings, $destinatario, $mensaje);
                        $estado = 'enviado';
                    } elseif ($regla->canal === 'whatsapp' && $settings?->whatsapp_token) {
                        $this->enviarWhatsapp($settings, $destinatario, $mensaje);
                        $estado = 'enviado';
                    }
                } catch (\Exception $e) {
                    $estado = 'fallido';
                    $error  = $e->getMessage();
                }

                AutomationLog::create([
                    'automation_rule_id' => $regla->id,
                    'user_id'            => $regla->user_id,
                    'cita_id'            => $cita->id,
                    'cliente_id'         => $cita->cliente_id,
                    'canal'              => $regla->canal,
                    'destinatario'       => $destinatario,
                    'mensaje'            => $mensaje,
                    'estado'             => $estado,
                    'error'              => $error,
                ]);
            }

            $regla->update(['ultima_ejecucion' => now()]);
        }
    }

private function enviarEmail(Setting $settings, string $to, string $mensaje, string $nombre): void
{
    config([
        'mail.mailers.smtp.host'       => $settings->mail_host,
        'mail.mailers.smtp.port'       => $settings->mail_port ?? 465,
        'mail.mailers.smtp.username'   => $settings->mail_username,
        'mail.mailers.smtp.password'   => $settings->mail_password,
        'mail.mailers.smtp.encryption' => $settings->mail_port == 465 ? 'ssl' : 'tls',
        'mail.from.address'            => $settings->mail_from_address,
        'mail.from.name'               => $settings->mail_from_name ?? $nombre,
    ]);

    Mail::raw($mensaje, function (Message $msg) use ($to, $nombre) {
        $msg->to($to)->subject('Recordatorio de cita - ' . $nombre);
    });
}
    private function enviarTelegram(Setting $settings, string $chatId, string $mensaje): void
    {
        $url = "https://api.telegram.org/bot{$settings->telegram_bot_token}/sendMessage";
        file_get_contents($url . '?' . http_build_query([
            'chat_id' => $chatId,
            'text'    => $mensaje,
        ]));
    }

    private function enviarWhatsapp(Setting $settings, string $telefono, string $mensaje): void
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

    private function construirMensaje(AutomationRule $regla, Cita $cita): string
    {
        $plantilla = $regla->config['mensaje'] ?? 'Hola {nombre}, te recordamos tu cita el {fecha} a las {hora} con {empleado} para {servicio}.';
        return str_replace(
            ['{nombre}', '{fecha}', '{hora}', '{empleado}', '{servicio}'],
            [
                $cita->cliente->nombre,
                \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y'),
                \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i'),
                $cita->empleado->nombre,
                $cita->servicio->nombre,
            ],
            $plantilla
        );
    }

    private function getDestinatario(string $canal, $cliente): string
    {
        return match($canal) {
            'email'    => $cliente->email ?? '',
            'telegram' => $cliente->telefono ?? '',
            'whatsapp' => $cliente->telefono ?? '',
            default    => '',
        };
    }
}
