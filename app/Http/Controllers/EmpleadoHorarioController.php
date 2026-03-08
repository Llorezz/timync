<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\EmpleadoHorario;
use App\Models\EmpleadoDiaLibre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoHorarioController extends Controller
{
    public function index(Empleado $empleado)
    {
        abort_if($empleado->user_id !== Auth::id(), 403);

        // Crear horarios para los 7 días si no existen
        for ($dia = 0; $dia <= 6; $dia++) {
            EmpleadoHorario::firstOrCreate(
                ['empleado_id' => $empleado->id, 'dia_semana' => $dia],
                [
                    'activo'              => in_array($dia, [1,2,3,4,5]),
                    'hora_inicio_manana'  => '09:00',
                    'hora_fin_manana'     => '14:00',
                    'hora_inicio_tarde'   => '16:00',
                    'hora_fin_tarde'      => '20:00',
                ]
            );
        }

        $horarios   = $empleado->horarios;
        $diasLibres = $empleado->diasLibres()->where('fecha_fin', '>=', now())->get();

        return view('empleados.horarios', compact('empleado', 'horarios', 'diasLibres'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        abort_if($empleado->user_id !== Auth::id(), 403);

        $request->validate([
            'horarios'                        => 'array',
            'horarios.*.activo'               => 'nullable|boolean',
            'horarios.*.hora_inicio_manana'   => 'nullable|date_format:H:i',
            'horarios.*.hora_fin_manana'      => 'nullable|date_format:H:i',
            'horarios.*.hora_inicio_tarde'    => 'nullable|date_format:H:i',
            'horarios.*.hora_fin_tarde'       => 'nullable|date_format:H:i',
        ]);

        foreach ($request->horarios ?? [] as $id => $datos) {
            EmpleadoHorario::where('id', $id)
                ->where('empleado_id', $empleado->id)
                ->update([
                    'activo'             => isset($datos['activo']),
                    'hora_inicio_manana' => $datos['hora_inicio_manana'] ?? null,
                    'hora_fin_manana'    => $datos['hora_fin_manana'] ?? null,
                    'hora_inicio_tarde'  => $datos['hora_inicio_tarde'] ?? null,
                    'hora_fin_tarde'     => $datos['hora_fin_tarde'] ?? null,
                ]);
        }

        return redirect()->route('empleados.horarios', $empleado)
                         ->with('success', 'Horarios actualizados correctamente.');
    }

    public function storeDiaLibre(Request $request, Empleado $empleado)
    {
        abort_if($empleado->user_id !== Auth::id(), 403);

        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'motivo'       => 'nullable|string|max:255',
        ]);

        EmpleadoDiaLibre::create([
            'empleado_id'  => $empleado->id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'motivo'       => $request->motivo,
        ]);

        return redirect()->route('empleados.horarios', $empleado)
                         ->with('success', 'Día libre añadido.');
    }

    public function destroyDiaLibre(Empleado $empleado, EmpleadoDiaLibre $diaLibre)
    {
        abort_if($empleado->user_id !== Auth::id(), 403);
        $diaLibre->delete();
        return redirect()->route('empleados.horarios', $empleado)
                         ->with('success', 'Día libre eliminado.');
    }
}
