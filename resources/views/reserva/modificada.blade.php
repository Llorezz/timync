<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita modificada</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .card { background: white; border-radius: 20px; padding: 48px 40px; max-width: 460px; width: 100%; box-shadow: 0 4px 24px rgba(0,0,0,0.08); text-align: center; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; text-align: left; }
        td { padding: 10px 12px; font-size: 14px; border-bottom: 1px solid #f1f5f9; }
        td:first-child { color: #64748b; }
        td:last-child { font-weight: 600; color: #0f172a; }
    </style>
</head>
<body>
    <div class="card">
        <div style="width:72px; height:72px; background:#d1fae5; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 24px; font-size:32px;">✅</div>
        <h1 style="font-size:22px; font-weight:800; color:#0f172a; margin-bottom:8px;">¡Cita modificada!</h1>
        <p style="color:#64748b; font-size:14px; margin-bottom:4px;">Tu cita ha sido actualizada correctamente.</p>
        <table>
            <tr>
                <td>📅 Nueva fecha</td>
                <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>🕐 Nueva hora</td>
                <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</td>
            </tr>
            <tr>
                <td>✂️ Servicio</td>
                <td>{{ $cita->servicio->nombre }}</td>
            </tr>
            @if($cita->empleado)
            <tr>
                <td>👤 Profesional</td>
                <td>{{ $cita->empleado->nombre }}</td>
            </tr>
            @endif
        </table>
    </div>
</body>
</html>
