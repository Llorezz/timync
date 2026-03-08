<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita confirmada · {{ $negocio->nombre_negocio ?? $negocio->name }}</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; }
        .card { background: white; border-radius: 16px; padding: 48px 40px; max-width: 480px; width: 100%; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <div class="card">
        <div style="width:72px; height:72px; background:#d1fae5; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 24px; font-size:32px;">
            ✅
        </div>
        <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin-bottom:12px;">¡Reserva confirmada!</h1>
        <p style="color:#64748b; font-size:15px; line-height:1.6; margin-bottom:32px;">
            Tu cita ha sido reservada correctamente. Recibirás un email de confirmación en breve.
        </p>
        <div style="background:#f8fafc; border-radius:8px; padding:16px; margin-bottom:32px; font-size:13px; color:#334155; text-align:left;">
            <div style="font-weight:600; color:#0f172a; margin-bottom:8px;">📍 {{ $negocio->nombre_negocio ?? $negocio->name }}</div>
            @if($negocio->telefono_negocio)
                <div>📞 {{ $negocio->telefono_negocio }}</div>
            @endif
        </div>
        <a href="{{ route('negocio.show', $negocio->slug) }}" style="display:inline-block; background:{{ $negocio->color_primario ?? '#0f4c81' }}; color:white; padding:12px 32px; border-radius:8px; text-decoration:none; font-weight:600; font-size:15px;">
            Hacer otra reserva
        </a>
    </div>
</body>
</html>
