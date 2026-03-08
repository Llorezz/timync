<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
public function index(Request $request)
{
    $query = Cliente::where('user_id', Auth::id());

    if ($request->filled('buscar')) {
        $query->where(function($q) use ($request) {
            $q->where('nombre', 'like', '%' . $request->buscar . '%')
              ->orWhere('email', 'like', '%' . $request->buscar . '%')
              ->orWhere('telefono', 'like', '%' . $request->buscar . '%');
        });
    }

    $clientes = $query->latest()->paginate(10)->withQueryString();
    return view('clientes.index', compact('clientes'));
}
    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'notas'    => 'nullable|string',
        ]);

        Cliente::create([
            'user_id'  => Auth::id(),
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'notas'    => $request->notas,
        ]);

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente creado correctamente.');
    }

    public function show(Cliente $cliente)
    {
        abort_if($cliente->user_id !== Auth::id(), 403);
        $citas = $cliente->citas()->with(['empleado', 'servicio'])->latest()->get();
        return view('clientes.show', compact('cliente', 'citas'));
    }

    public function edit(Cliente $cliente)
    {
        abort_if($cliente->user_id !== Auth::id(), 403);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        abort_if($cliente->user_id !== Auth::id(), 403);

        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'notas'    => 'nullable|string',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        abort_if($cliente->user_id !== Auth::id(), 403);
        $cliente->delete();
        return redirect()->route('clientes.index')
                         ->with('success', 'Cliente eliminado.');
    }
}
