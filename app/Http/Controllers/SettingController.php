<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use App\Models\Setting;
use App\Models\HorarioNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate(['user_id' => Auth::id()]);
        $user    = Auth::user();

        $diasExistentes = HorarioNegocio::where('user_id', Auth::id())->pluck('dia_semana')->toArray();
        for ($i = 0; $i <= 6; $i++) {
            if (!in_array($i, $diasExistentes)) {
                HorarioNegocio::create([
                    'user_id'       => Auth::id(),
                    'dia_semana'    => $i,
                    'activo'        => $i < 5,
                    'hora_apertura' => '09:00',
                    'hora_cierre'   => '20:00',
                ]);
            }
        }

        $horarios = HorarioNegocio::where('user_id', Auth::id())
            ->orderBy('dia_semana')
            ->get()
            ->keyBy('dia_semana');

        return view('configuracion.index', compact('setting', 'user', 'horarios'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'negocio_nombre'     => 'nullable|string|max:255',
            'telefono'           => 'nullable|string|max:20',
            'email'              => 'nullable|email',
            'direccion'          => 'nullable|string|max:255',
            'ciudad'             => 'nullable|string|max:100',
            'descripcion_negocio'=> 'nullable|string',
            'instagram'          => 'nullable|string|max:255',
            'facebook'           => 'nullable|string|max:255',
            'whatsapp_negocio'   => 'nullable|string|max:20',
            'slug'               => 'nullable|string|max:100|unique:users,slug,' . Auth::id(),
            'foto_portada'       => 'nullable|image|max:2048',
            'fotos_galeria.*'    => 'nullable|image|max:2048',
            'mail_host'          => 'nullable|string',
            'mail_port'          => 'nullable|integer',
            'mail_username'      => 'nullable|string',
            'mail_from_address'  => 'nullable|email',
            'mail_from_name'     => 'nullable|string',
            'telegram_bot_token' => 'nullable|string',
            'telegram_chat_id'   => 'nullable|string',
            'whatsapp_token'     => 'nullable|string',
            'whatsapp_phone_id'  => 'nullable|string',
        ]);

        $setting = Setting::firstOrCreate(['user_id' => Auth::id()]);
        $user    = Auth::user();

        $userData = [
            'nombre_negocio'     => $request->negocio_nombre,
            'telefono_negocio'   => $request->telefono,
            'direccion'          => $request->direccion,
            'ciudad'             => $request->ciudad,
            'descripcion_negocio'=> $request->descripcion_negocio,
            'instagram'          => $request->instagram,
            'facebook'           => $request->facebook,
            'whatsapp_negocio'   => $request->whatsapp_negocio,
        ];

        if ($request->filled('slug')) {
            $userData['slug'] = \Illuminate\Support\Str::slug($request->slug);
        }

if ($request->hasFile('foto_portada')) {
    if ($user->foto_portada) Storage::disk('public')->delete($user->foto_portada);
    $userData['foto_portada'] = ImageService::guardar($request->file('foto_portada'), 'portadas', 1400, 80);
}

if ($request->hasFile('fotos_galeria')) {
    $fotos = $user->fotos_galeria ?? [];
    foreach ($request->file('fotos_galeria') as $foto) {
        $fotos[] = ImageService::guardar($foto, 'galeria', 1200, 75);
    }
    $userData['fotos_galeria'] = $fotos;
}
        $user->update($userData);

        // Guardar horarios por día
        for ($i = 0; $i <= 6; $i++) {
            HorarioNegocio::updateOrCreate(
                ['user_id' => Auth::id(), 'dia_semana' => $i],
                [
                    'activo'        => $request->boolean('horario_activo_' . $i),
                    'hora_apertura' => $request->input('hora_apertura_' . $i),
                    'hora_cierre'   => $request->input('hora_cierre_' . $i),
                ]
            );
        }

        $settingData = [
            'negocio_nombre'     => $request->negocio_nombre,
            'telefono'           => $request->telefono,
            'negocio_email'       => $request->email,
            'mail_host'          => $request->mail_host,
            'mail_port'          => $request->mail_port,
            'mail_username'      => $request->mail_username,
            'mail_from_address'  => $request->mail_from_address,
            'mail_from_name'     => $request->mail_from_name,
            'telegram_bot_token' => $request->telegram_bot_token,
            'telegram_chat_id'   => $request->telegram_chat_id,
            'whatsapp_token'     => $request->whatsapp_token,
            'whatsapp_phone_id'  => $request->whatsapp_phone_id,
        ];

        if ($request->filled('mail_password')) {
            $settingData['mail_password'] = $request->mail_password;
        }

        $setting->update($settingData);

        return redirect()->route('configuracion.index')
                         ->with('success', 'Configuración guardada correctamente.');
    }

    public function eliminarFotoGaleria(Request $request)
    {
        $user  = Auth::user();
        $fotos = $user->fotos_galeria ?? [];
        $foto  = $request->foto;

        Storage::disk('public')->delete($foto);
        $user->update(['fotos_galeria' => array_values(array_filter($fotos, fn($f) => $f !== $foto))]);

        return back()->with('success', 'Foto eliminada.');
    }
}
