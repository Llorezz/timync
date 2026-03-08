<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider, string $slug)
    {
        Session::put('reserva_slug', $slug);
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            $slug = Session::get('reserva_slug');
            return redirect('/reserva/' . $slug)->with('error', 'No se pudo autenticar.');
        }

        $slug = Session::get('reserva_slug');
        $negocio = \App\Models\User::where('slug', $slug)->firstOrFail();

        $email  = $socialUser->getEmail();
        $nombre = $socialUser->getName();
        // 1. Buscar por email
        $cliente = Cliente::where('email', $email)->where('user_id', $negocio->id)->first();
        if (!$cliente) {
            // 2. No existe por email -> crear nuevo (telefono se completara al reservar)
            $cliente = Cliente::create([
                'nombre'   => $nombre,
                'email'    => $email,
                'user_id'  => $negocio->id,
                'avatar'   => $socialUser->getAvatar(),
                'provider' => $provider,
            ]);
        } else {
            // Actualizar avatar si no tiene
            if (!$cliente->avatar) $cliente->update(['avatar' => $socialUser->getAvatar()]);
        }

        Session::put('social_cliente', [
            'id'               => $cliente->id,
            'nombre'           => $cliente->nombre,
            'email'            => $cliente->email,
            'avatar'           => $cliente->avatar,
            'politica_aceptada'=> $cliente->politica_aceptada,
        ]);

        return redirect('/reserva/' . $slug);
    }

    public function aceptarPolitica(Request $request, string $slug)
    {
        $clienteId = Session::get('social_cliente.id');
        if (!$clienteId) return redirect('/reserva/' . $slug);

        $cliente = Cliente::find($clienteId);
        if ($cliente) {
            $cliente->update([
                'politica_aceptada'    => true,
                'politica_aceptada_at' => now(),
            ]);
            Session::put('social_cliente.politica_aceptada', true);
        }

        return redirect('/reserva/' . $slug);
    }
}
