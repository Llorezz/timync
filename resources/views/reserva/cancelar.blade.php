<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar cita</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .card { background: white; border-radius: 20px; padding: 40px; max-width: 540px; width: 100%; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .btn { display: inline-block; padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; border: none; text-decoration: none; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.9; }
        .btn-primary { background: #0f4c81; color: white; width: 100%; text-align: center; display: block; }
        .btn-danger { background: #ef4444; color: white; width: 100%; text-align: center; display: block; }
        .btn-secondary { background: #f1f5f9; color: #64748b; width: 100%; text-align: center; display: block; }
        table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        td { padding: 10px 12px; font-size: 14px; border-bottom: 1px solid #f1f5f9; }
        td:first-child { color: #64748b; width: 40%; }
        td:last-child { font-weight: 600; color: #0f172a; }
        input, select { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; }
        input:focus, select:focus { border-color: #0f4c81; }
        label { font-size: 13px; font-weight: 600; color: #475569; display: block; margin-bottom: 6px; }
        .slot { padding: 8px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 13px; cursor: pointer; background: white; text-align: center; transition: all 0.15s; font-weight: 500; }
        .slot:hover { border-color: #0f4c81; color: #0f4c81; background: #e8f0f9; }
        .slot.selected { background: #0f4c81; color: white; border-color: #0f4c81; }
        .section { display: none; }
        .section.active { display: block; }
    </style>
</head>
<body>
    <div class="card">
        @if(isset($error))
            <div style="text-align:center;">
                <div style="font-size:48px; margin-bottom:16px;">⚠️</div>
                <h1 style="font-size:20px; font-weight:800; color:#0f172a; margin-bottom:8px;">Enlace no válido</h1>
                <p style="color:#64748b; font-size:14px;">{{ $error }}</p>
            </div>
        @else
            <!-- CABECERA -->
            <div style="text-align:center; margin-bottom:24px;">
                <div style="font-size:40px; margin-bottom:12px;">🗓️</div>
                <h1 style="font-size:20px; font-weight:800; color:#0f172a;">Gestionar tu cita</h1>
                <p style="color:#64748b; font-size:13px; margin-top:4px;">Puedes modificar la fecha/hora o cancelar tu cita</p>
            </div>

            <!-- DETALLES ACTUALES -->
            <div style="background:#f0f9ff; border-radius:12px; padding:16px; border-left:4px solid #0f4c81; margin-bottom:24px;">
                <div style="font-size:13px; font-weight:600; color:#0f172a; margin-bottom:10px;">📋 Tu cita actual</div>
                <table>
                    <tr>
                        <td>📅 Fecha</td>
                        <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>🕐 Hora</td>
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

            <!-- OPCIONES -->
            <div class="section active" id="opciones">
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <button onclick="mostrar('modificar')" class="btn btn-primary">✏️ Cambiar fecha y hora</button>
                    <button onclick="mostrar('cancelar')" class="btn btn-danger">❌ Cancelar mi cita</button>
                </div>
            </div>

            <!-- MODIFICAR -->
            <div class="section" id="modificar">
                <h2 style="font-size:15px; font-weight:700; color:#0f172a; margin-bottom:16px;">Nueva fecha y hora</h2>
                <form action="{{ route('cita.modificar', $token) }}" method="POST">
                    @csrf
                    <input type="hidden" name="fecha_hora" id="nueva-fecha-hora">
                    <div style="margin-bottom:16px;">
                        <label>Selecciona una fecha</label>
                        <input type="date" id="nueva-fecha" min="{{ now()->addDay()->format('Y-m-d') }}" onchange="cargarSlots()">
                    </div>
                    <div id="slots-loading" style="display:none; text-align:center; padding:16px; color:#94a3b8; font-size:13px;">⏳ Cargando horarios...</div>
                    <div id="slots-empty" style="display:none; text-align:center; padding:16px; color:#94a3b8; font-size:13px;">😔 No hay horas disponibles este día.</div>
                    <div id="slots-container" style="display:none; margin-bottom:16px;">
                        <label style="margin-bottom:8px;">Hora disponible</label>
                        <div id="slots-grid" style="display:grid; grid-template-columns:repeat(4,1fr); gap:8px;"></div>
                    </div>
                    <div style="display:flex; gap:10px; margin-top:16px;">
                        <button type="button" onclick="mostrar('opciones')" class="btn btn-secondary">← Atrás</button>
                        <button type="submit" id="btn-confirmar" class="btn btn-primary" disabled style="opacity:0.4;">✅ Confirmar cambio</button>
                    </div>
                </form>
            </div>

            <!-- CANCELAR -->
            <div class="section" id="cancelar">
                <div style="text-align:center; padding:16px 0;">
                    <div style="font-size:36px; margin-bottom:12px;">⚠️</div>
                    <p style="color:#64748b; font-size:14px; margin-bottom:20px;">¿Estás seguro de que quieres cancelar esta cita? Esta acción no se puede deshacer.</p>
                    <form action="{{ route('cita.cancelar', $token) }}" method="POST">
                        @csrf
                        <div style="display:flex; gap:10px;">
                            <button type="button" onclick="mostrar('opciones')" class="btn btn-secondary">← Atrás</button>
                            <button type="submit" class="btn btn-danger">Sí, cancelar mi cita</button>
                        </div>
                    </form>
                </div>
            </div>

        @endif
    </div>

    <script>
        const slug    = '{{ $cita->user->slug }}';
        const servId  = {{ $cita->servicio_id }};
        const empId   = {{ $cita->empleado_id ?? 'null' }};

        function mostrar(seccion) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.getElementById(seccion).classList.add('active');
        }

        function cargarSlots() {
            const fecha = document.getElementById('nueva-fecha').value;
            if (!fecha) return;

            document.getElementById('slots-container').style.display = 'none';
            document.getElementById('slots-empty').style.display     = 'none';
            document.getElementById('slots-loading').style.display   = 'block';
            document.getElementById('nueva-fecha-hora').value        = '';
            document.getElementById('btn-confirmar').disabled        = true;
            document.getElementById('btn-confirmar').style.opacity   = '0.4';

            fetch(`/reserva/${slug}/disponibilidad?fecha=${fecha}&servicio_id=${servId}&empleado_id=${empId || ''}`)
                .then(r => r.json())
                .then(slots => {
                    document.getElementById('slots-loading').style.display = 'none';
                    if (!slots.length) {
                        document.getElementById('slots-empty').style.display = 'block';
                        return;
                    }
                    document.getElementById('slots-container').style.display = 'block';
                    const grid = document.getElementById('slots-grid');
                    grid.innerHTML = '';
                    slots.forEach(slot => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'slot';
                        btn.textContent = slot;
                        btn.onclick = () => seleccionarSlot(btn, fecha, slot);
                        grid.appendChild(btn);
                    });
                });
        }

        function seleccionarSlot(btn, fecha, hora) {
            document.querySelectorAll('.slot').forEach(s => s.classList.remove('selected'));
            btn.classList.add('selected');
            document.getElementById('nueva-fecha-hora').value      = fecha + ' ' + hora + ':00';
            document.getElementById('btn-confirmar').disabled      = false;
            document.getElementById('btn-confirmar').style.opacity = '1';
        }
    </script>
</body>
</html>
