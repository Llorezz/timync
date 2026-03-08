<?php

namespace App\Jobs;

use App\Models\AutomationLog;
use App\Models\AutomationRule;
use App\Models\Cliente;
use App\Models\Setting;
use App\Services\NotificacionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcesarClientesInactivos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $reglas = AutomationRule::where('tipo', 'cliente_inactivo')
            ->where('activo', true)
            ->get();

        foreach ($reglas as $regla) {
            $settings        = Setting::where('user_id', $regla->user_id)->first();
            $diasInactividad = $regla->config['dias_inactividad'] ?? 30;

            $clientes = Cliente::where('user_id', $regla->user_id)
                ->whereDoesntHave('citas', function ($q) use ($diasInactividad) {
                    $q->where('fecha_hora', '>=', now()->subDays($diasInactividad));
                })
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
                    $regla->config['mensaje'] ?? 'Hola {nombre}, hace tiempo que no te vemos. ¡Tenemos una oferta especial para ti!'
                );

                $destinatario = $regla->canal === 'email' ? $cliente->email : $cliente->telefono;
                $estado = 'pendiente';
                $error  = null;

                try {
                    if ($settings && $destinatario) {
                        NotificacionService::enviar($settings, $regla->canal, $destinatario, $mensaje, 'Te echamos de menos');
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

            $regla->update(['ultima_ejecucion' => now()]);
        }
    }
}
