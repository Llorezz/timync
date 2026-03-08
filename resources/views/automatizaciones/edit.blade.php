<x-app-layout>
    <x-slot name="header">Editar automatización</x-slot>

    <div class="card" style="padding:32px; max-width:700px;">

        @if($errors->any())
            <div class="alert-error" style="margin-bottom:24px;">
                <ul style="margin:0; padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('automatizaciones.update', $automatizacion) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                <div style="grid-column:span 2;">
                    <label>Nombre de la regla *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $automatizacion->nombre) }}">
                </div>

                <div>
                    <label>Tipo de automatización *</label>
                    <select name="tipo" id="tipo" onchange="mostrarOpciones()">
                        <option value="recordatorio" {{ $automatizacion->tipo == 'recordatorio' ? 'selected' : '' }}>📅 Recordatorio de cita</option>
                        <option value="no_show" {{ $automatizacion->tipo == 'no_show' ? 'selected' : '' }}>🚫 Cliente no-show</option>
                        <option value="hueco_libre" {{ $automatizacion->tipo == 'hueco_libre' ? 'selected' : '' }}>📢 Huecos libres</option>
                        <option value="cliente_inactivo" {{ $automatizacion->tipo == 'cliente_inactivo' ? 'selected' : '' }}>💤 Cliente inactivo</option>
                    </select>
                </div>

                <div>
                    <label>Canal de envío *</label>
                    <select name="canal">
                        <option value="email" {{ $automatizacion->canal == 'email' ? 'selected' : '' }}>📧 Email</option>
                        <option value="telegram" {{ $automatizacion->canal == 'telegram' ? 'selected' : '' }}>✈️ Telegram</option>
                        <option value="whatsapp" {{ $automatizacion->canal == 'whatsapp' ? 'selected' : '' }}>💬 WhatsApp</option>
                    </select>
                </div>

                <div style="grid-column:span 2;">
                    <label>Estado</label>
                    <select name="activo">
                        <option value="1" {{ $automatizacion->activo ? 'selected' : '' }}>Activa</option>
                        <option value="0" {{ !$automatizacion->activo ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>

                <!-- Opciones recordatorio -->
                <div id="opciones_recordatorio" style="grid-column:span 2; display:none;">
                    <div style="background:#f8fafc; border-radius:8px; padding:20px;">
                        <div style="font-size:13px; font-weight:600; color:#475569; margin-bottom:16px;">⚙️ Configuración del recordatorio</div>
                        <div style="margin-bottom:16px;">
                            <label>Horas antes de la cita</label>
                            <input type="number" name="horas_antes" value="{{ old('horas_antes', $automatizacion->config['horas_antes'] ?? 24) }}" min="1" max="72">
                        </div>
                        <div>
                            <label>Mensaje <span style="color:#94a3b8; font-weight:400;">— Variables: {nombre} {fecha} {hora} {empleado} {servicio}</span></label>
                            <textarea name="mensaje" rows="3">{{ old('mensaje', $automatizacion->config['mensaje'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Opciones no-show -->
                <div id="opciones_no_show" style="grid-column:span 2; display:none;">
                    <div style="background:#f8fafc; border-radius:8px; padding:20px;">
                        <div style="font-size:13px; font-weight:600; color:#475569; margin-bottom:16px;">⚙️ Configuración no-show</div>
                        <div style="margin-bottom:16px;">
                            <label>Minutos de espera tras la hora de la cita</label>
                            <input type="number" name="minutos_espera" value="{{ old('minutos_espera', $automatizacion->config['minutos_espera'] ?? 30) }}" min="5">
                        </div>
                        <div>
                            <label>Mensaje <span style="color:#94a3b8; font-weight:400;">— Variables: {nombre} {fecha} {servicio}</span></label>
                            <textarea name="mensaje" rows="3">{{ old('mensaje', $automatizacion->config['mensaje'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Opciones hueco libre -->
                <div id="opciones_hueco_libre" style="grid-column:span 2; display:none;">
                    <div style="background:#f8fafc; border-radius:8px; padding:20px;">
                        <div style="font-size:13px; font-weight:600; color:#475569; margin-bottom:16px;">⚙️ Configuración huecos libres</div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                            <div>
                                <label>Días de anticipación a revisar</label>
                                <input type="number" name="dias_anticipacion" value="{{ old('dias_anticipacion', $automatizacion->config['dias_anticipacion'] ?? 2) }}" min="1">
                            </div>
                            <div>
                                <label>Mínimo de citas para no enviar</label>
                                <input type="number" name="min_citas" value="{{ old('min_citas', $automatizacion->config['min_citas'] ?? 3) }}" min="1">
                            </div>
                        </div>
                        <div>
                            <label>Mensaje <span style="color:#94a3b8; font-weight:400;">— Variables: {nombre}</span></label>
                            <textarea name="mensaje" rows="3">{{ old('mensaje', $automatizacion->config['mensaje'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Opciones cliente inactivo -->
                <div id="opciones_cliente_inactivo" style="grid-column:span 2; display:none;">
                    <div style="background:#f8fafc; border-radius:8px; padding:20px;">
                        <div style="font-size:13px; font-weight:600; color:#475569; margin-bottom:16px;">⚙️ Configuración cliente inactivo</div>
                        <div style="margin-bottom:16px;">
                            <label>Días sin cita para considerarlo inactivo</label>
                            <input type="number" name="dias_inactividad" value="{{ old('dias_inactividad', $automatizacion->config['dias_inactividad'] ?? 30) }}" min="1">
                        </div>
                        <div>
                            <label>Mensaje <span style="color:#94a3b8; font-weight:400;">— Variables: {nombre}</span></label>
                            <textarea name="mensaje" rows="3">{{ old('mensaje', $automatizacion->config['mensaje'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div style="display:flex; gap:12px; margin-top:28px;">
                <button type="submit" class="btn-primary">Actualizar automatización</button>
                <a href="{{ route('automatizaciones.index') }}" style="padding:8px 18px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500;">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        function mostrarOpciones() {
            const tipo = document.getElementById('tipo').value;
            const secciones = ['recordatorio', 'no_show', 'hueco_libre', 'cliente_inactivo'];
            secciones.forEach(s => {
                document.getElementById('opciones_' + s).style.display = 'none';
            });
            if (tipo) {
                document.getElementById('opciones_' + tipo).style.display = 'block';
            }
        }
        mostrarOpciones();
    </script>

</x-app-layout>
