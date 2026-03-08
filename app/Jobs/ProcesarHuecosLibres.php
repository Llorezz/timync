<?php

namespace App\Jobs;

use App\Models\AutomationLog;
use App\Models\AutomationRule;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Setting;
use App\Services\NotificacionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcesarHuecosLibres implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $reglas = AutomationRule::where('tipo', 'hueco_libre')
            ->where('activo', true)
            ->get();

        foreach ($reglas as $regla) {
            $settings          = Setting::where('user_id', $regla->user_id)->first();
            $diasAnticipacion  = $regla->config['dias_anticipacion'] ?? 2;
            $fechaDesde        = now()->addDays(1)->startOfDay();
            $fechaHasta        = now()->addDays($diasAnticipacion)->endOfDay();

            $citasOcupadas = Cita::where('user_id', $regla->user_id)
                ->whereBetween('fecha_hora', [$fechaDesde, $fechaHasta])
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->count();

            if ($citasOcupadas < ($regla->config['min_citas'] ?? 3)) {
                $clientes = Cliente::where('user_id', $regla->user_id)
                    ->latest()
                    ->take(10)
                    ->get();

                foreach ($clientes as $cliente) {
                    $yaEnviado = AutomationLog::where('automation_rule_id', $regla->id)
                        ->where('cliente_id', $cliente->id)
                        ->whereDate('created_at', today())
                        ->exists();

                    if ($yaEnviado) continue;

                    $mensaje = str_replace(
                        '{nombre}',
                        $cliente->nombre,
                        $regla->config['mensaje'] ?? 'Hola {nombre}, tenemos huecos disponibles próximamente. ¡Reserva tu cita ahora!'
                    );

                    $destinatario = $regla->canal === 'email' ? $cliente->email : $cliente->telefono;
                    $estado = 'pendiente';
                    $error  = null;

                    try {
                        if ($settings && $destinatario) {
                            NotificacionService::enviar($settings, $regla->canal, $destinatario, $mensaje, 'Huecos disponibles');
                            $estado = 'enviado';
                        }
                    } catch (\Exception $e) {
                        $estado = 'fallido';
                        $error  = $e->getMessage();
                    }

                    AutomationLog::create([
                        'automation_rule_id' => $regla->id,
                        'user_id'            => $regla->user_id,
                        'cliente_id'         => $cliente->id,
                        'canal'              => $regla->canal,
                        'destinatario'       => $destinatario ?? '',
                        'mensaje'            => $mensaje,
                        'estado'             => $estado,
                        'error'              => $error,
                    ]);
                }
            }

            $regla->update(['ultima_ejecucion' => now()]);
        }
    }
}
