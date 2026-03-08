<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\CitaToken;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use App\Models\User;
use App\Services\NotificacionService;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function show(string $slug)
    {
        $negocio   = User::where('slug', $slug)->where('activo', true)->firstOrFail();
        $servicios = Servicio::where('user_id', $negocio->id)->where('activo', true)->get();
        $empleados = Empleado::where('user_id', $negocio->id)->where('activo', true)->get();
        $setting = \App\Models\Setting::where('user_id', $negocio->id)->first();
        return view('reserva.show', compact('negocio', 'servicios', 'empleados', 'setting'));
    }

    public function disponibilidad(Request $request, string $slug)
    {
        $negocio    = User::where('slug', $slug)->where('activo', true)->firstOrFail();
        $fecha      = Carbon::parse($request->fecha);
$servicio   = $request->servicio_id ? Servicio::find($request->servicio_id) : null;
        $empleadoId = $request->empleado_id;

        $citasOcupadas = Cita::where('user_id', $negocio->id)
            ->whereDate('fecha_hora', $fecha)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->when($empleadoId, fn($q) => $q->where('empleado_id', $empleadoId))
            ->get(['fecha_hora', 'fecha_fin']);

        // Usar horario del negocio para ese día
        $diaSemana = $fecha->dayOfWeek === 0 ? 6 : $fecha->dayOfWeek - 1;
        $horario   = \App\Models\HorarioNegocio::where('user_id', $negocio->id)
            ->where('dia_semana', $diaSemana)
            ->where('activo', true)
            ->first();

        if (!$horario) return response()->json([]);

        $slots    = [];
        $inicio   = $fecha->copy()->setTimeFromTimeString($horario->hora_apertura);
        $fin      = $fecha->copy()->setTimeFromTimeString($horario->hora_cierre);
$duracion = $servicio ? $servicio->duracion_minutos : 30;
        while ($inicio->copy()->addMinutes($duracion)->lte($fin)) {
            $slotFin = $inicio->copy()->addMinutes($duracion);
            $ocupado = false;

            foreach ($citasOcupadas as $cita) {
                $citaInicio = Carbon::parse($cita->fecha_hora);
                $citaFin    = Carbon::parse($cita->fecha_fin);
                if ($inicio->lt($citaFin) && $slotFin->gt($citaInicio)) {
                    $ocupado = true;
                    break;
                }
            }

            if (!$ocupado && $inicio->gt(now())) {
                $slots[] = $inicio->format('H:i');
            }

            $inicio->addMinutes(30);
        }

        return response()->json($slots);
    }

    public function store(Request $request, string $slug)
    {
        $negocio = User::where('slug', $slug)->where('activo', true)->firstOrFail();

        $request->validate([
            'nombre'      => 'required|string|max:255',
            'email'       => 'required|email',
            'telefono'    => 'required|string|max:20',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha_hora'  => 'required|date|after:now',
            'empleado_id' => 'nullable|exists:empleados,id',
        ]);

        // 1. Buscar por email
        $cliente = Cliente::where('email', $request->email)->where('user_id', $negocio->id)->first();
        if (!$cliente && $request->telefono) {
            // 2. Buscar por telefono
            $cliente = Cliente::where('telefono', $request->telefono)->where('user_id', $negocio->id)->first();
            if ($cliente) {
                // Mismo cliente con distinto email -> actualizar email
                $cliente->update(['email' => $request->email, 'nombre' => $request->nombre]);
            }
        }
        if (!$cliente) {
            // 3. Nuevo cliente
            $cliente = Cliente::create([
                'user_id'  => $negocio->id,
                'nombre'   => $request->nombre,
                'telefono' => $request->telefono,
                'email'    => $request->email,
            ]);
        } else {
            // Actualizar telefono y nombre si estaban vacios
            $updates = [];
            if (!$cliente->telefono && $request->telefono) $updates['telefono'] = $request->telefono;
            if (!$cliente->nombre && $request->nombre) $updates['nombre'] = $request->nombre;
            if ($updates) $cliente->update($updates);
        }

        $cita = Cita::create([
            'user_id'     => $negocio->id,
            'cliente_id'  => $cliente->id,
            'empleado_id' => $request->empleado_id,
            'servicio_id' => $request->servicio_id,
            'fecha_hora'  => $request->fecha_hora,
            'estado'      => 'pendiente',
            'notas'       => $request->notas,
        ]);

        $cita->load(['servicio', 'empleado']);

        // Generar token de cancelación
        $token = CitaToken::create([
            'cita_id'    => $cita->id,
            'token'      => Str::random(64),
            'expires_at' => Carbon::parse($cita->fecha_hora)->subHours(2),
        ]);

        $urlCancelar = url('/cancelar-cita/' . $token->token);

        // Enviar email de confirmación
        $settings = Setting::where('user_id', $negocio->id)->first();
        if ($settings) {
            try {
                $nombreNegocio = $negocio->nombre_negocio ?? $negocio->name;
                $fecha         = Carbon::parse($cita->fecha_hora)->format('d/m/Y');
                $hora          = Carbon::parse($cita->fecha_hora)->format('H:i');
                $servicio      = $cita->servicio->nombre;
                $empleado      = $cita->empleado?->nombre ?? null;
                $precio        = number_format($cita->servicio->precio, 2) . '€';
                $duracion      = $cita->servicio->duracion_minutos . ' min';

                $html = $this->generarEmailConfirmacion(
                    $cliente->nombre, $nombreNegocio, $fecha, $hora,
                    $servicio, $empleado, $precio, $duracion,
                    $urlCancelar, $negocio
                );

                NotificacionService::enviarEmail(
                    $settings,
                    $cliente->email,
                    $html,
                    '✅ Cita confirmada - ' . $nombreNegocio,
                    true
                );
            } catch (\Exception $e) {
                // Silencioso
            }
        }

        return redirect()->route('negocio.confirmacion', $slug)
                         ->with('cita_id', $cita->id);
    }

    private function generarEmailConfirmacion(
        string $nombre, string $negocio, string $fecha, string $hora,
        string $servicio, ?string $empleado, string $precio, string $duracion,
        string $urlCancelar, User $negocioModel
    ): string {
$empleadoRow = $empleado ? "<tr><td style='padding:8px 0; color:#64748b; font-size:14px;'>👤 Profesional</td><td style='padding:8px 0; font-size:14px; font-weight:600; color:#0f172a;'>{$empleado}</td></tr>" : '';
$ciudadStr   = $negocioModel->ciudad ? ', ' . $negocioModel->ciudad : '';
$direccion   = $negocioModel->direccion ? "<p style='margin:4px 0; color:#64748b; font-size:13px;'>📍 {$negocioModel->direccion}{$ciudadStr}</p>" : '';
$telefono    = $negocioModel->telefono_negocio ? "<p style='margin:4px 0; color:#64748b; font-size:13px;'>📞 {$negocioModel->telefono_negocio}</p>" : '';

        return "<!DOCTYPE html>
<html lang='es'>
<head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'></head>
<body style='margin:0; padding:0; background:#f0f4f8; font-family:Inter,-apple-system,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background:#f0f4f8; padding:40px 20px;'>
<tr><td align='center'>
<table width='560' cellpadding='0' cellspacing='0' style='max-width:560px; width:100%;'>

  <!-- HEADER -->
  <tr><td style='background:linear-gradient(135deg,#0f4c81,#1a6eb5); border-radius:16px 16px 0 0; padding:32px; text-align:center;'>
    <div style='font-size:48px; margin-bottom:12px;'>✅</div>
    <h1 style='color:white; font-size:22px; font-weight:800; margin:0;'>¡Cita confirmada!</h1>
    <p style='color:rgba(255,255,255,0.8); font-size:14px; margin:8px 0 0;'>Hola {$nombre}, tu reserva está lista</p>
  </td></tr>

  <!-- DETALLES -->
  <tr><td style='background:white; padding:32px;'>
    <h2 style='font-size:15px; font-weight:700; color:#0f172a; margin:0 0 20px;'>📋 Detalles de tu cita</h2>
    <table width='100%' cellpadding='0' cellspacing='0' style='border-collapse:collapse;'>
      <tr style='background:#f8fafc;'><td colspan='2' style='padding:4px 12px; border-radius:8px 8px 0 0;'></td></tr>
      <tr style='border-bottom:1px solid #f1f5f9;'>
        <td style='padding:12px; color:#64748b; font-size:14px;'>📅 Fecha</td>
        <td style='padding:12px; font-size:14px; font-weight:700; color:#0f172a;'>{$fecha}</td>
      </tr>
      <tr style='border-bottom:1px solid #f1f5f9;'>
        <td style='padding:12px; color:#64748b; font-size:14px;'>🕐 Hora</td>
        <td style='padding:12px; font-size:14px; font-weight:700; color:#0f172a;'>{$hora}</td>
      </tr>
      <tr style='border-bottom:1px solid #f1f5f9;'>
        <td style='padding:12px; color:#64748b; font-size:14px;'>✂️ Servicio</td>
        <td style='padding:12px; font-size:14px; font-weight:700; color:#0f172a;'>{$servicio}</td>
      </tr>
      {$empleadoRow}
      <tr style='border-bottom:1px solid #f1f5f9;'>
        <td style='padding:12px; color:#64748b; font-size:14px;'>⏱ Duración</td>
        <td style='padding:12px; font-size:14px; font-weight:600; color:#0f172a;'>{$duracion}</td>
      </tr>
      <tr>
        <td style='padding:12px; color:#64748b; font-size:14px;'>💰 Precio</td>
        <td style='padding:12px; font-size:14px; font-weight:700; color:#0f4c81;'>{$precio}</td>
      </tr>
    </table>

    <!-- INFO NEGOCIO -->
    <div style='background:#f0f9ff; border-radius:12px; padding:16px; margin-top:24px; border-left:4px solid #0f4c81;'>
      <p style='margin:0 0 8px; font-weight:700; color:#0f172a; font-size:14px;'>📍 {$negocio}</p>
      {$direccion}
      {$telefono}
    </div>

    <!-- CANCELAR -->
    <div style='margin-top:28px; padding:20px; background:#fff8f0; border-radius:12px; border:1px solid #fed7aa; text-align:center;'>
      <p style='margin:0 0 12px; font-size:13px; color:#64748b;'>¿Necesitas cancelar tu cita? Puedes hacerlo hasta 2 horas antes.</p>
      <a href='{$urlCancelar}' style='display:inline-block; background:#ef4444; color:white; padding:10px 24px; border-radius:8px; text-decoration:none; font-weight:600; font-size:14px;'>Cancelar mi cita</a>
    </div>
  </td></tr>

  <!-- FOOTER -->
  <tr><td style='background:#f8fafc; border-radius:0 0 16px 16px; padding:20px; text-align:center; border-top:1px solid #e2e8f0;'>
    <p style='margin:0; font-size:12px; color:#94a3b8;'>Este email fue enviado automáticamente por <strong>Timync</strong></p>
    <p style='margin:4px 0 0; font-size:12px; color:#94a3b8;'>© " . date('Y') . " Timync · Sistema de gestión de citas</p>
  </td></tr>

</table>
</td></tr>
</table>
</body>
</html>";
    }

    public function confirmacion(string $slug)
    {
        $negocio = User::where('slug', $slug)->where('activo', true)->firstOrFail();
        return view('reserva.confirmacion', compact('negocio'));
    }

    public function cancelarForm(string $token)
    {
        $citaToken = CitaToken::where('token', $token)->firstOrFail();

        if ($citaToken->expires_at->isPast()) {
            return view('reserva.cancelar', ['error' => 'El enlace de cancelación ha expirado.', 'cita' => null]);
        }

        $cita = $citaToken->cita->load(['servicio', 'empleado', 'cliente']);

        if ($cita->estado === 'cancelada') {
            return view('reserva.cancelar', ['error' => 'Esta cita ya fue cancelada.', 'cita' => null]);
        }

        return view('reserva.cancelar', compact('cita', 'token'));
    }

public function modificar(Request $request, string $token)
{
    $citaToken = CitaToken::where('token', $token)->firstOrFail();

    if ($citaToken->expires_at->isPast()) {
        return back()->with('error', 'El enlace ha expirado.');
    }

    $request->validate(['fecha_hora' => 'required|date|after:now']);

    $cita = $citaToken->cita;
    $cita->update(['fecha_hora' => $request->fecha_hora]);

    // Actualizar expiración del token
    $citaToken->update(['expires_at' => \Carbon\Carbon::parse($request->fecha_hora)->subHours(2)]);

    return view('reserva.modificada', ['cita' => $cita->fresh(['servicio', 'empleado'])]);
}
public function ocupacion(string $slug, Request $request)
{
    $negocio = User::where('slug', $slug)->where('activo', true)->firstOrFail();
    $mes     = $request->get('mes', now()->format('Y-m'));
    $inicio  = Carbon::parse($mes . '-01')->startOfMonth();
    $fin     = Carbon::parse($mes . '-01')->endOfMonth();

    $citas = Cita::where('user_id', $negocio->id)
        ->whereBetween('fecha_hora', [$inicio, $fin])
        ->whereNotIn('estado', ['cancelada'])
        ->with('servicio')
        ->get();

    $minutosPorDia = [];
    foreach ($citas as $cita) {
        $dia = Carbon::parse($cita->fecha_hora)->format('Y-m-d');
        $duracion = $cita->servicio?->duracion_minutos ?? 30;
        $minutosPorDia[$dia] = ($minutosPorDia[$dia] ?? 0) + $duracion;
    }

    $horarios  = $negocio->horariosNegocio()->where('activo', true)->get()->keyBy('dia_semana');
    $resultado = [];

    for ($d = $inicio->copy(); $d->lte($fin); $d->addDay()) {
        $fecha     = $d->format('Y-m-d');
        $diaSemana = $d->dayOfWeek === 0 ? 6 : $d->dayOfWeek - 1;
        $horario   = $horarios->get($diaSemana);

        if (!$horario || !$horario->activo) {
            $resultado[$fecha] = 'cerrado';
            continue;
        }

        $apertura        = Carbon::parse($fecha . ' ' . $horario->hora_apertura);
        $cierre          = Carbon::parse($fecha . ' ' . $horario->hora_cierre);
        $minutosTotal    = max(1, $apertura->diffInMinutes($cierre));
        $minutosOcupados = $minutosPorDia[$fecha] ?? 0;
        $porcentaje      = $minutosOcupados / $minutosTotal;

        if ($porcentaje >= 1)       $resultado[$fecha] = 'lleno';
        elseif ($porcentaje >= 0.7) $resultado[$fecha] = 'alto';
        elseif ($porcentaje >= 0.4) $resultado[$fecha] = 'medio';
        else                        $resultado[$fecha] = 'libre';
    }

    return response()->json($resultado);
}
    $horarios  = $negocio->horariosNegocio()->where("activo", true)->get()->keyBy("dia_semana");
    $resultado = [];
    for ($d = $inicio->copy(); $d->lte($fin); $d->addDay()) {
        $fecha     = $d->format("Y-m-d");
        $diaSemana = $d->dayOfWeek === 0 ? 6 : $d->dayOfWeek - 1;
        $horario   = $horarios->get($diaSemana);
        if (!$horario || !$horario->activo) { $resultado[$fecha] = "cerrado"; continue; }
        $apertura        = Carbon::parse($fecha . " " . $horario->hora_apertura);
        $cierre          = Carbon::parse($fecha . " " . $horario->hora_cierre);
        $minutosTotal    = max(1, $apertura->diffInMinutes($cierre));
        $minutosOcupados = $minutosPorDia[$fecha] ?? 0;
        $porcentaje      = $minutosOcupados / $minutosTotal;
        if ($porcentaje >= 1)         $resultado[$fecha] = "lleno";
        elseif ($porcentaje >= 0.7)   $resultado[$fecha] = "alto";
        elseif ($porcentaje >= 0.4)   $resultado[$fecha] = "medio";
        else                          $resultado[$fecha] = "libre";
    }
    return response()->json($resultado);

    public function cancelar(Request $request, string $token)
    {
        $citaToken = CitaToken::where('token', $token)->firstOrFail();

        if ($citaToken->expires_at->isPast()) {
            return back()->with('error', 'El enlace ha expirado.');
        }

        $cita = $citaToken->cita;
        $cita->update(['estado' => 'cancelada']);
        $citaToken->delete();

        return view('reserva.cancelada');
    }
}
