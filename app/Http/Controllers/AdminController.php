<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cita;
use App\Models\Cliente;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $negocios      = User::where('rol', 'negocio')->latest()->paginate(15);
        $totalNegocios = User::where('rol', 'negocio')->count();
        $totalCitas    = Cita::count();
        $totalClientes = Cliente::count();
        $nuevosHoy     = User::where('rol', 'negocio')->whereDate('created_at', today())->count();

        return view('admin.index', compact('negocios', 'totalNegocios', 'totalCitas', 'totalClientes', 'nuevosHoy'));
    }

    public function show(User $user)
    {
        $citas    = Cita::where('user_id', $user->id)->count();
        $clientes = Cliente::where('user_id', $user->id)->count();
        return view('admin.show', compact('user', 'citas', 'clientes'));
    }

    public function toggleActivo(User $user)
    {
        $user->update(['activo' => !$user->activo]);
        return redirect()->route('admin.index')
                         ->with('success', 'Estado del negocio actualizado.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.index')
                         ->with('success', 'Negocio eliminado.');
    }
}
