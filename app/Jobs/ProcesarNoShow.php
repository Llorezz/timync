<?php

namespace App\Jobs;

use App\Models\AutomationLog;
use App\Models\AutomationRule;
use App\Models\Cita;
use App\Models\Setting;
use App\Services\NotificacionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcesarNoShow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $reglas = AutomationRule::where('tipo', 'no_show')
            ->where('activo', true)
            ->get();

        foreach ($reglas as $regla) {
            $settings      = Setting::where('user_id', $regla->user_id)->first();
            $minutosEspera = $regla->config['minutos_espera'] ?? 30;

            $citas = Cita::with(['cliente', 'empleado', 'servicio'])
                ->where('user_id', $regla->user_id)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->where('fecha_hora', '<', now()->subMinutes($minutosEspera))
                ->get();

            foreach ($citas as $cita) {
                $cita->update(['estado' => 'cancelada']);

                if (!$cita->cliente) continue;

                $mensaje = str_replace(
                    ['{nombre}', '{fecha}', '{servicio}'],
                    [
                        $cita->cliente->nombre,
                        \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i'),
                        $cita->servicio->nombre,
                    ],
                    $regla->config['mensaje'] ?? 'Hola {nombre}, notamos que no pudiste asistir a tu cita del {fecha}. ¿Te gustaría reagendarla?'
                );

                $destinatario = $regla->canal === 'email' ? $cita->cliente->email : $cita->cliente->telefono;
                $estado = 'pendiente';
                $error  = null;

                try {
                    if ($settings) {
                        NotificacionService::enviar($settings, $regla->canal, $destinatario ?? '', $mensaje, 'No-show detectado');
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
                    'destinatario'       => $destinatario ?? '',
                    'mensaje'            => $mensaje,
                    'estado'             => $estado,
                    'error'              => $error,
                ]);
            }

            $regla->update(['ultima_ejecucion' => now()]);
        }
    }
}
