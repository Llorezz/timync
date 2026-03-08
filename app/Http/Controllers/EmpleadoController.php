<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::where('user_id', Auth::id())->latest()->paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('empleados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'email'        => 'required|email|unique:empleados',
            'telefono'     => 'nullable|string|max:20',
            'especialidad' => 'nullable|string|max:255',
        ]);

        Empleado::create([
            'user_id'      => Auth::id(),
            'nombre'       => $request->nombre,
            'email'        => $request->email,
            'telefono'     => $request->telefono,
            'especialidad' => $request->especialidad,
        ]);

        return redirect()->route('empleados.index')
                         ->with('success', 'Empleado creado correctamente.');
    }

    public function edit(Empleado $empleado)
    {
        abort_if($empleado->user_id !== Auth::id(), 403);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        abort_if($empleado->user_id !== Auth::id(), 403);

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'email'        => 'required|email|unique:empleados,email,' . $empleado->id,
            'telefono'     => 'nullable|string|max:20',
            'especialidad' => 'nullable|string|max:255',
        ]);

        $empleado->update($request->all());

        return redirect()->route('empleados.index')
                         ->with('success', 'Empleado actualizado.');
    }

    public function destroy(Empleado $empleado)
    {
        abort_if($empleado->user_id !== Auth::id(), 403);
        $empleado->delete();
        return redirect()->route('empleados.index')
                         ->with('success', 'Empleado eliminado.');
    }
}
