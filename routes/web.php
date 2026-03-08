<?php

use App\Http\Controllers\ReservaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmpleadoHorarioController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CitaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $userId = Auth::id();
    $citasHoy = \App\Models\Cita::where('user_id', $userId)->whereDate('fecha_hora', today())->count();
    $proximasCitas = \App\Models\Cita::where('user_id', $userId)->whereBetween('fecha_hora', [now(), now()->addDays(7)])->count();
    $totalEmpleados = \App\Models\Empleado::where('user_id', $userId)->where('activo', true)->count();
    $ingresosMes = \App\Models\Cita::where('citas.user_id', $userId)
        ->whereMonth('citas.fecha_hora', now()->month)
        ->whereYear('citas.fecha_hora', now()->year)
        ->where('citas.estado', 'confirmada')
        ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
        ->sum('servicios.precio');
    $citas = \App\Models\Cita::with(['empleado', 'servicio'])
        ->where('user_id', $userId)
        ->where('fecha_hora', '>=', now())
        ->orderBy('fecha_hora')
        ->take(5)
        ->get();
    return view('dashboard', compact('citasHoy', 'proximasCitas', 'totalEmpleados', 'ingresosMes', 'citas'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/empleados/{empleado}/horarios', [EmpleadoHorarioController::class, 'index'])->name('empleados.horarios');
    Route::put('/empleados/{empleado}/horarios', [EmpleadoHorarioController::class, 'update'])->name('empleados.horarios.update');
    Route::post('/empleados/{empleado}/dias-libres', [EmpleadoHorarioController::class, 'storeDiaLibre'])->name('empleados.dias-libres.store');
    Route::delete('/empleados/{empleado}/dias-libres/{diaLibre}', [EmpleadoHorarioController::class, 'destroyDiaLibre'])->name('empleados.dias-libres.destroy');

    Route::get('/estadisticas', function () {
        $userId = Auth::id();
        $año    = request('año', now()->year);
        $citasPorEstado = \App\Models\Cita::where('user_id', $userId)->whereYear('fecha_hora', $año)->selectRaw('estado, count(*) as total')->groupBy('estado')->pluck('total', 'estado');
        $citasPorEmpleado = \App\Models\Cita::where('citas.user_id', $userId)->whereYear('citas.fecha_hora', $año)->join('empleados', 'citas.empleado_id', '=', 'empleados.id')->selectRaw('empleados.nombre, count(*) as total')->groupBy('empleados.nombre')->orderByDesc('total')->get();
        $clientesPorMes = \App\Models\Cliente::where('user_id', $userId)->whereYear('created_at', $año)->selectRaw('month(created_at) as mes, count(*) as total')->groupBy('mes')->pluck('total', 'mes');
        $serviciosTop = \App\Models\Cita::where('citas.user_id', $userId)->whereYear('citas.fecha_hora', $año)->join('servicios', 'citas.servicio_id', '=', 'servicios.id')->selectRaw('servicios.nombre, count(*) as total')->groupBy('servicios.nombre')->orderByDesc('total')->take(5)->get();
        $canceladasPorMes = \App\Models\Cita::where('user_id', $userId)->whereYear('fecha_hora', $año)->where('estado', 'cancelada')->selectRaw('month(fecha_hora) as mes, count(*) as total')->groupBy('mes')->pluck('total', 'mes');
        return view('estadisticas', compact('citasPorEstado', 'citasPorEmpleado', 'clientesPorMes', 'serviciosTop', 'canceladasPorMes', 'año'));
    })->name('estadisticas');

    Route::resource('email-templates', EmailTemplateController::class);
    Route::get('email-templates/{emailTemplate}/preview', [EmailTemplateController::class, 'preview'])->name('email-templates.preview');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/configuracion', [SettingController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion/eliminar-foto', [SettingController::class, 'eliminarFotoGaleria'])->name('configuracion.eliminar-foto');
    Route::put('/configuracion', [SettingController::class, 'update'])->name('configuracion.update');

    Route::resource('empleados', EmpleadoController::class);
    Route::resource('servicios', ServicioController::class);
    Route::resource('automatizaciones', AutomationController::class)->parameters(['automatizaciones' => 'automatizacion']);
    Route::resource('clientes', ClienteController::class);

    Route::get('/citas/calendario', function () {
        return view('citas.calendario');
    })->name('citas.calendario');

    Route::get('/citas/calendario/datos', function () {
        $citas = \App\Models\Cita::with(['empleado', 'servicio'])
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($cita) {
                $color = match($cita->estado) {
                    'confirmada' => '#10b981',
                    'cancelada'  => '#ef4444',
                    default      => '#0f4c81',
                };
                return [
                    'id'    => $cita->id,
                    'title' => ($cita->servicio->nombre ?? '') . ' · ' . ($cita->empleado->nombre ?? ''),
                    'start' => $cita->fecha_hora,
                    'end'   => $cita->fecha_fin,
                    'color' => $color,
                    'extendedProps' => [
                        'estado'   => $cita->estado,
                        'empleado' => $cita->empleado->nombre ?? '—',
                        'servicio' => $cita->servicio->nombre ?? '—',
                        'notas'    => $cita->notas,
                        'edit_url' => route('citas.edit', $cita->id),
                    ],
                ];
            });
        return response()->json($citas);
    })->name('citas.calendario.datos');

    Route::post('/citas/{cita}/estado', function (\App\Models\Cita $cita, \Illuminate\Http\Request $request) {
        abort_if($cita->user_id !== Auth::id(), 403);
        $request->validate(['estado' => 'required|in:pendiente,confirmada,cancelada']);
        $cita->update(['estado' => $request->estado]);
        return response()->json(['ok' => true]);
    })->name('citas.estado');

    Route::resource('citas', CitaController::class);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/{user}', [AdminController::class, 'show'])->name('show');
    Route::post('/{user}/toggle', [AdminController::class, 'toggleActivo'])->name('toggle');
    Route::delete('/{user}', [AdminController::class, 'destroy'])->name('destroy');
});
Route::post('/auth/otp/enviar/{slug}', [App\Http\Controllers\OtpController::class, 'enviar'])->name('otp.enviar');
Route::post('/auth/otp/verificar/{slug}', [App\Http\Controllers\OtpController::class, 'verificar'])->name('otp.verificar');
Route::get('/reserva/{slug}', [ReservaController::class, 'show'])->name('negocio.show');
Route::get('/reserva/{slug}/disponibilidad', [ReservaController::class, 'disponibilidad'])->name('negocio.disponibilidad');
Route::post('/reserva/{slug}', [ReservaController::class, 'store'])->name('negocio.store');
Route::get('/reserva/{slug}/ocupacion', [ReservaController::class, 'ocupacion'])->name('negocio.ocupacion');
Route::get('/cancelar-cita/{token}', [ReservaController::class, 'cancelarForm'])->name('cita.cancelar.form');
Route::post('/cancelar-cita/{token}', [ReservaController::class, 'cancelar'])->name('cita.cancelar');
Route::post('/cancelar-cita/{token}/modificar', [ReservaController::class, 'modificar'])->name('cita.modificar');
Route::get('/reserva/{slug}/confirmacion', [ReservaController::class, 'confirmacion'])->name('negocio.confirmacion');
Route::get('/auth/{provider}/redirect/{slug}', [App\Http\Controllers\SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [App\Http\Controllers\SocialAuthController::class, 'callback'])->name('social.callback');
Route::post('/auth/politica/{slug}', [App\Http\Controllers\SocialAuthController::class, 'aceptarPolitica'])->name('social.politica');
Route::get('/auth/logout/{slug}', function($slug) {
    session()->forget('social_cliente');
    return redirect('/reserva/' . $slug);
})->name('social.logout');

require __DIR__.'/auth.php';
