<x-app-layout>
    <x-slot name="header">Nueva plantilla de email</x-slot>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">

        <!-- Editor -->
        <div class="card" style="padding:28px;">
            <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:20px;">✏️ Editor</h3>

            @if($errors->any())
                <div class="alert-error" style="margin-bottom:24px;">
                    <ul style="margin:0; padding-left:16px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('email-templates.store') }}" method="POST" id="form-template">
                @csrf

                <div style="margin-bottom:16px;">
                    <label>Nombre de la plantilla *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Recordatorio elegante">
                </div>

                <div style="margin-bottom:16px;">
                    <label>Tipo de automatización</label>
                    <select name="tipo">
                        <option value="">General (cualquier uso)</option>
                        <option value="recordatorio" {{ old('tipo') == 'recordatorio' ? 'selected' : '' }}>📅 Recordatorio</option>
                        <option value="no_show" {{ old('tipo') == 'no_show' ? 'selected' : '' }}>🚫 No-show</option>
                        <option value="hueco_libre" {{ old('tipo') == 'hueco_libre' ? 'selected' : '' }}>📢 Hueco libre</option>
                        <option value="cliente_inactivo" {{ old('tipo') == 'cliente_inactivo' ? 'selected' : '' }}>💤 Cliente inactivo</option>
                    </select>
                </div>

                <div style="margin-bottom:16px;">
                    <label>Asunto del email *</label>
                    <input type="text" name="asunto" id="asunto" value="{{ old('asunto') }}" placeholder="Ej: Recordatorio de tu cita con {nombre}">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div>
                        <label>Color principal</label>
                        <div style="display:flex; gap:8px; align-items:center;">
                            <input type="color" name="color_primario" id="color_primario" value="{{ old('color_primario', '#0f4c81') }}" style="width:48px; height:38px; padding:2px; border-radius:6px;" onchange="actualizarPreview()">
                            <input type="text" id="color_primario_text" value="{{ old('color_primario', '#0f4c81') }}" style="flex:1;" onchange="document.getElementById('color_primario').value=this.value; actualizarPreview()">
                        </div>
                    </div>
                    <div>
                        <label>Color del botón</label>
                        <div style="display:flex; gap:8px; align-items:center;">
                            <input type="color" name="color_boton" id="color_boton" value="{{ old('color_boton', '#00b4d8') }}" style="width:48px; height:38px; padding:2px; border-radius:6px;" onchange="actualizarPreview()">
                            <input type="text" id="color_boton_text" value="{{ old('color_boton', '#00b4d8') }}" style="flex:1;" onchange="document.getElementById('color_boton').value=this.value; actualizarPreview()">
                        </div>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div>
                        <label>Texto del botón</label>
                        <input type="text" name="texto_boton" id="texto_boton" value="{{ old('texto_boton') }}" placeholder="Ej: Ver mi cita" onkeyup="actualizarPreview()">
                    </div>
                    <div>
                        <label>URL del botón</label>
                        <input type="url" name="url_boton" value="{{ old('url_boton') }}" placeholder="https://...">
                    </div>
                </div>

                <div style="margin-bottom:8px;">
                    <label>Cuerpo del email *</label>
                    <div style="font-size:12px; color:#94a3b8; margin-bottom:8px;">
                        Variables disponibles: <code>{nombre}</code> <code>{fecha}</code> <code>{hora}</code> <code>{empleado}</code> <code>{servicio}</code>
                    </div>
                    <textarea name="cuerpo" id="cuerpo" rows="8" placeholder="Hola {nombre}, te recordamos tu cita..." onkeyup="actualizarPreview()">{{ old('cuerpo') }}</textarea>
                </div>

                <!-- Plantillas predefinidas -->
                <div style="margin-bottom:20px;">
                    <label>Usar plantilla base</label>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <button type="button" onclick="usarPlantilla('recordatorio')" style="padding:6px 12px; border:1px solid #e2e8f0; border-radius:6px; font-size:12px; color:#64748b; background:white; cursor:pointer;">📅 Recordatorio</button>
                        <button type="button" onclick="usarPlantilla('noshow')" style="padding:6px 12px; border:1px solid #e2e8f0; border-radius:6px; font-size:12px; color:#64748b; background:white; cursor:pointer;">🚫 No-show</button>
                        <button type="button" onclick="usarPlantilla('oferta')" style="padding:6px 12px; border:1px solid #e2e8f0; border-radius:6px; font-size:12px; color:#64748b; background:white; cursor:pointer;">🎁 Oferta</button>
                    </div>
                </div>

                <div style="display:flex; gap:12px;">
                    <button type="submit" class="btn-primary">Guardar plantilla</button>
                    <a href="{{ route('email-templates.index') }}" style="padding:8px 18px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500;">Cancelar</a>
                </div>
            </form>
        </div>

        <!-- Preview en tiempo real -->
        <div>
            <div style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:16px;">👁 Preview en tiempo real</div>
            <div id="preview-container" style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; background:#f8fafc;">
                <iframe id="preview-frame" style="width:100%; height:600px; border:none;" srcdoc=""></iframe>
            </div>
        </div>

    </div>

    <script>
        function generarHTML() {
            const colorPrimario = document.getElementById('color_primario').value;
            const colorBoton    = document.getElementById('color_boton').value;
            const textoBoton    = document.getElementById('texto_boton').value;
            const cuerpo        = document.getElementById('cuerpo').value.replace(/\n/g, '<br>');
            const asunto        = document.getElementById('asunto').value;

            return `<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0; padding:0; background:#f0f4f8; font-family:'Helvetica Neue',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8; padding:40px 20px;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08);">
  <tr><td style="background:${colorPrimario}; padding:32px; text-align:center;">
    <div style="color:white; font-size:24px; font-weight:700; letter-spacing:-0.5px;">Timync</div>
    <div style="color:rgba(255,255,255,0.7); font-size:13px; margin-top:4px;">${asunto}</div>
  </td></tr>
  <tr><td style="padding:40px 48px;">
    <div style="color:#334155; font-size:15px; line-height:1.7;">${cuerpo || 'Escribe el contenido del email...'}</div>
    ${textoBoton ? `<div style="text-align:center; margin-top:32px;">
      <a href="#" style="background:${colorBoton}; color:white; padding:14px 32px; border-radius:8px; text-decoration:none; font-weight:600; font-size:15px; display:inline-block;">${textoBoton}</a>
    </div>` : ''}
  </td></tr>
  <tr><td style="background:#f8fafc; padding:24px 48px; border-top:1px solid #e2e8f0; text-align:center;">
    <div style="color:#94a3b8; font-size:12px;">Enviado por Timync · Sistema de gestión de citas</div>
  </td></tr>
</table>
</td></tr>
</table>
</body></html>`;
        }

        function actualizarPreview() {
            document.getElementById('color_primario_text').value = document.getElementById('color_primario').value;
            document.getElementById('color_boton_text').value    = document.getElementById('color_boton').value;
            document.getElementById('preview-frame').srcdoc      = generarHTML();
        }

        function usarPlantilla(tipo) {
            const plantillas = {
                recordatorio: {
                    asunto: 'Recordatorio: Tu cita es mañana',
                    cuerpo: 'Hola {nombre},\n\nTe recordamos que tienes una cita programada para el {fecha} a las {hora}.\n\nEmpleado: {empleado}\nServicio: {servicio}\n\nSi necesitas cancelar o modificar tu cita, contáctanos con antelación.\n\n¡Te esperamos!'
                },
                noshow: {
                    asunto: 'Te echamos de menos, {nombre}',
                    cuerpo: 'Hola {nombre},\n\nNotamos que no pudiste asistir a tu cita del {fecha} para {servicio}.\n\nEntendemos que pueden surgir imprevistos. ¿Te gustaría reagendar tu cita?\n\nEstamos aquí para ayudarte.'
                },
                oferta: {
                    asunto: '🎁 Oferta especial para ti, {nombre}',
                    cuerpo: 'Hola {nombre},\n\nHace tiempo que no te vemos y te echamos de menos.\n\nPor eso queremos ofrecerte un descuento especial en tu próxima visita.\n\n¡Reserva ahora y aprovecha esta oferta exclusiva!'
                }
            };

            if (plantillas[tipo]) {
                document.getElementById('asunto').value = plantillas[tipo].asunto;
                document.getElementById('cuerpo').value  = plantillas[tipo].cuerpo;
                actualizarPreview();
            }
        }

        // Preview inicial
        actualizarPreview();
    </script>

</x-app-layout>
