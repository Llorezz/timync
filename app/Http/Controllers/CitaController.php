<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cita::with(['empleado', 'servicio', 'cliente'])
                     ->where('user_id', Auth::id());

        // Filtros
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        $citas     = $query->latest()->paginate(10)->withQueryString();
        $empleados = Empleado::where('user_id', Auth::id())->where('activo', true)->get();
        $clientes  = Cliente::where('user_id', Auth::id())->orderBy('nombre')->get();

        return view('citas.index', compact('citas', 'empleados', 'clientes'));
    }

    public function create()
    {
        $empleados = Empleado::where('user_id', Auth::id())->where('activo', true)->get();
        $servicios = Servicio::where('user_id', Auth::id())->where('activo', true)->get();
        $clientes  = Cliente::where('user_id', Auth::id())->orderBy('nombre')->get();
        return view('citas.create', compact('empleados', 'servicios', 'clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'nullable|exists:empleados,id',
            'servicio_id' => 'required|exists:servicios,id',
            'cliente_id'  => 'nullable|exists:clientes,id',
            'fecha_hora'  => 'required|date|after:now',
            'notas'       => 'nullable|string',
        ]);

        Cita::create([
            'user_id'     => Auth::id(),
            'cliente_id'  => $request->cliente_id,
            'empleado_id' => $request->empleado_id,
            'servicio_id' => $request->servicio_id,
            'fecha_hora'  => $request->fecha_hora,
            'notas'       => $request->notas,
        ]);

        return redirect()->route('citas.index')
                         ->with('success', 'Cita creada correctamente.');
    }

    public function edit(Cita $cita)
    {
        abort_if($cita->user_id !== Auth::id(), 403);
        $empleados = Empleado::where('user_id', Auth::id())->where('activo', true)->get();
        $servicios = Servicio::where('user_id', Auth::id())->where('activo', true)->get();
        $clientes  = Cliente::where('user_id', Auth::id())->orderBy('nombre')->get();
        return view('citas.edit', compact('cita', 'empleados', 'servicios', 'clientes'));
    }

    public function update(Request $request, Cita $cita)
    {
        abort_if($cita->user_id !== Auth::id(), 403);

        $request->validate([
            'empleado_id' => 'nullable|exists:empleados,id',
            'servicio_id' => 'required|exists:servicios,id',
            'cliente_id'  => 'nullable|exists:clientes,id',
            'fecha_hora'  => 'required|date',
            'estado'      => 'required|in:pendiente,confirmada,cancelada',
            'notas'       => 'nullable|string',
        ]);

        $cita->update($request->all());

        return redirect()->route('citas.index')
                         ->with('success', 'Cita actualizada.');
    }

    public function destroy(Cita $cita)
    {
        abort_if($cita->user_id !== Auth::id(), 403);
        $cita->delete();
        return redirect()->route('citas.index')
                         ->with('success', 'Cita eliminada.');
    }
}
