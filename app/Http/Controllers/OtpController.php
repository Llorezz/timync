<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\OtpToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OtpController extends Controller
{
    public function enviar(Request $request, string $slug)
    {
        $request->validate(['email' => 'required|email']);

        $negocio = \App\Models\User::where('slug', $slug)->firstOrFail();
        $email   = $request->email;
        $codigo  = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Eliminar OTPs anteriores para este email+slug
        OtpToken::where('email', $email)->where('slug', $slug)->delete();

        OtpToken::create([
            'email'      => $email,
            'slug'       => $slug,
            'codigo'     => $codigo,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Enviar email
        $nombreNegocio = $negocio->nombre_negocio ?? $negocio->name;
        Mail::html("
            <div style='font-family:Inter,sans-serif;max-width:480px;margin:0 auto;padding:32px 24px;'>
                <div style='background:linear-gradient(135deg,#0f4c81,#1a6eb5);border-radius:16px;padding:28px;text-align:center;margin-bottom:24px;'>
                    <div style='color:white;font-size:22px;font-weight:800;'>Tu código de verificación</div>
                    <div style='color:rgba(255,255,255,0.8);font-size:14px;margin-top:6px;'>{$nombreNegocio}</div>
                </div>
                <div style='text-align:center;margin-bottom:24px;'>
                    <div style='font-size:14px;color:#64748b;margin-bottom:16px;'>Usa este código para completar tu reserva:</div>
                    <div style='font-size:48px;font-weight:800;letter-spacing:12px;color:#0f4c81;background:#e8f0f9;border-radius:12px;padding:20px;display:inline-block;'>{$codigo}</div>
                    <div style='font-size:12px;color:#94a3b8;margin-top:12px;'>Válido durante 10 minutos</div>
                </div>
                <div style='background:#fef9c3;border-radius:10px;padding:12px 16px;font-size:12px;color:#854d0e;text-align:center;'>
                    Si no has solicitado este código, ignora este email.
                </div>
            </div>
        ", function($m) use ($email, $nombreNegocio) {
            $m->to($email)->subject("Tu código de verificación — {$nombreNegocio}");
        });

        Session::put('otp_email', $email);
        Session::put('otp_slug', $slug);

        return response()->json(['ok' => true]);
    }

    public function verificar(Request $request, string $slug)
    {
        $request->validate(['email' => 'required|email', 'codigo' => 'required|size:6']);

        $otp = OtpToken::where('email', $request->email)
            ->where('slug', $slug)
            ->where('codigo', $request->codigo)
            ->where('usado', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json(['ok' => false, 'error' => 'Código incorrecto o expirado.'], 422);
        }

        $otp->update(['usado' => true]);

        $negocio = \App\Models\User::where('slug', $slug)->firstOrFail();

        $email = $request->email;
        // 1. Buscar por email
        $cliente = Cliente::where('email', $email)->where('user_id', $negocio->id)->first();
        if (!$cliente) {
            // 2. Crear nuevo (telefono se cotejara al confirmar reserva)
            $cliente = Cliente::create([
                'nombre'   => explode('@', $email)[0],
                'email'    => $email,
                'user_id'  => $negocio->id,
                'provider' => 'email',
            ]);
        }

        Session::put('social_cliente', [
            'id'                => $cliente->id,
            'nombre'            => $cliente->nombre,
            'email'             => $cliente->email,
            'avatar'            => $cliente->avatar,
            'politica_aceptada' => $cliente->politica_aceptada,
            'provider'          => 'email',
        ]);

        return response()->json(['ok' => true, 'politica' => $cliente->politica_aceptada]);
    }
}
