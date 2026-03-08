<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use App\Models\Servicio;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    public function index()
    {
$servicios = Servicio::where('user_id', Auth::id())->latest()->paginate(10);
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        $empleados = Empleado::where('user_id', Auth::id())->where('activo', true)->get();
        return view('servicios.create', compact('empleados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'descripcion'      => 'nullable|string',
            'descripcion_larga'=> 'nullable|string',
            'precio'           => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:5',
            'empleado_id'      => 'nullable|exists:empleados,id',
            'foto'             => 'nullable|image|max:2048',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
$foto = ImageService::guardar($request->file('foto'), 'servicios', 800, 80);
        }

        Servicio::create([
            'user_id'          => Auth::id(),
            'nombre'           => $request->nombre,
            'descripcion'      => $request->descripcion,
            'descripcion_larga'=> $request->descripcion_larga,
            'precio'           => $request->precio,
            'duracion_minutos' => $request->duracion_minutos,
            'empleado_id'      => $request->empleado_id,
            'foto'             => $foto,
            'activo'           => true,
        ]);

        return redirect()->route('servicios.index')
                         ->with('success', 'Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        abort_if($servicio->user_id !== Auth::id(), 403);
        $empleados = Empleado::where('user_id', Auth::id())->where('activo', true)->get();
        return view('servicios.edit', compact('servicio', 'empleados'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        abort_if($servicio->user_id !== Auth::id(), 403);

        $request->validate([
            'nombre'           => 'required|string|max:255',
            'descripcion'      => 'nullable|string',
            'descripcion_larga'=> 'nullable|string',
            'precio'           => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:5',
            'empleado_id'      => 'nullable|exists:empleados,id',
            'foto'             => 'nullable|image|max:2048',
        ]);

        $foto = $servicio->foto;
        if ($request->hasFile('foto')) {
            if ($foto) Storage::disk('public')->delete($foto);
$foto = ImageService::guardar($request->file('foto'), 'servicios', 800, 80);
        }

        $servicio->update([
            'nombre'           => $request->nombre,
            'descripcion'      => $request->descripcion,
            'descripcion_larga'=> $request->descripcion_larga,
            'precio'           => $request->precio,
            'duracion_minutos' => $request->duracion_minutos,
            'empleado_id'      => $request->empleado_id,
            'foto'             => $foto,
            'activo'           => $request->boolean('activo', true),
        ]);

        return redirect()->route('servicios.index')
                         ->with('success', 'Servicio actualizado.');
    }

    public function destroy(Servicio $servicio)
    {
        abort_if($servicio->user_id !== Auth::id(), 403);
        if ($servicio->foto) Storage::disk('public')->delete($servicio->foto);
        $servicio->delete();
        return redirect()->route('servicios.index')
                         ->with('success', 'Servicio eliminado.');
    }
}
