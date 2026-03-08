<?php

namespace App\Http\Controllers;

use App\Models\AutomationRule;
use App\Models\AutomationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutomationController extends Controller
{
    public function index()
    {
        $reglas = AutomationRule::where('user_id', Auth::id())->latest()->get();
        $logs   = AutomationLog::where('user_id', Auth::id())->latest()->take(20)->get();
        return view('automatizaciones.index', compact('reglas', 'logs'));
    }

    public function create()
    {
        return view('automatizaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo'   => 'required|in:recordatorio,no_show,hueco_libre,cliente_inactivo',
            'canal'  => 'required|in:email,telegram,whatsapp',
        ]);

        $config = $this->buildConfig($request);

        AutomationRule::create([
            'user_id' => Auth::id(),
            'nombre'  => $request->nombre,
            'tipo'    => $request->tipo,
            'canal'   => $request->canal,
            'config'  => $config,
            'activo'  => true,
        ]);

        return redirect()->route('automatizaciones.index')
                         ->with('success', 'Automatización creada correctamente.');
    }

    public function edit(AutomationRule $automatizacion)
    {
        abort_if($automatizacion->user_id !== Auth::id(), 403);
        return view('automatizaciones.edit', compact('automatizacion'));
    }

    public function update(Request $request, AutomationRule $automatizacion)
    {
        abort_if($automatizacion->user_id !== Auth::id(), 403);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo'   => 'required|in:recordatorio,no_show,hueco_libre,cliente_inactivo',
            'canal'  => 'required|in:email,telegram,whatsapp',
        ]);

        $config = $this->buildConfig($request);

        $automatizacion->update([
            'nombre' => $request->nombre,
            'tipo'   => $request->tipo,
            'canal'  => $request->canal,
            'config' => $config,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()->route('automatizaciones.index')
                         ->with('success', 'Automatización actualizada.');
    }

    public function destroy(AutomationRule $automatizacion)
    {
        abort_if($automatizacion->user_id !== Auth::id(), 403);
        $automatizacion->delete();
        return redirect()->route('automatizaciones.index')
                         ->with('success', 'Automatización eliminada.');
    }

    private function buildConfig(Request $request): array
    {
        return match($request->tipo) {
            'recordatorio' => [
                'horas_antes' => $request->input('horas_antes', 24),
                'mensaje'     => $request->input('mensaje', 'Hola {nombre}, te recordamos tu cita el {fecha} a las {hora} con {empleado} para {servicio}.'),
            ],
            'no_show' => [
                'minutos_espera' => $request->input('minutos_espera', 30),
                'mensaje'        => $request->input('mensaje', 'Hola {nombre}, notamos que no pudiste asistir a tu cita del {fecha}. ¿Te gustaría reagendarla?'),
            ],
            'hueco_libre' => [
                'dias_anticipacion' => $request->input('dias_anticipacion', 2),
                'min_citas'         => $request->input('min_citas', 3),
                'mensaje'           => $request->input('mensaje', 'Hola {nombre}, tenemos huecos disponibles próximamente. ¡Reserva tu cita ahora!'),
            ],
            'cliente_inactivo' => [
                'dias_inactividad' => $request->input('dias_inactividad', 30),
                'mensaje'          => $request->input('mensaje', 'Hola {nombre}, hace tiempo que no te vemos. ¡Tenemos una oferta especial para ti!'),
            ],
            default => [],
        };
    }
}
