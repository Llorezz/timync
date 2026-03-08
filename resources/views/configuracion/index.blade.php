<x-app-layout>
    <x-slot name="header">Configuración</x-slot>

    @if(session('success'))
        <div class="alert-success" style="margin-bottom:24px;">{{ session('success') }}</div>
    @endif

    <div style="display:grid; grid-template-columns:220px 1fr; gap:24px; align-items:start;">

        <div class="card" style="padding:8px;">
            <nav>
                <button onclick="mostrarTab('negocio')" class="tab-btn active" id="tab-negocio">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
                    Datos del negocio
                </button>
                <button onclick="mostrarTab('reserva')" class="tab-btn" id="tab-reserva">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                    Página de reservas
                </button>
                <button onclick="mostrarTab('notificaciones')" class="tab-btn" id="tab-notificaciones">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    Notificaciones
                </button>
            </nav>
        </div>

        <form action="{{ route('configuracion.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- DATOS DEL NEGOCIO -->
            <div class="tab-content active" id="content-negocio">

                <div class="card" style="padding:28px; margin-bottom:20px;">
                    <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:20px;">🏢 Información del negocio</h3>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div>
                            <label>Nombre del negocio</label>
                            <input type="text" name="negocio_nombre" value="{{ old('negocio_nombre', $user->nombre_negocio) }}" placeholder="Mi negocio">
                        </div>
                        <div>
                            <label>Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono', $user->telefono_negocio) }}" placeholder="+34 600 000 000">
                        </div>
                        <div>
                            <label>Email de contacto</label>
                            <input type="email" name="email" value="{{ old('email', $setting->negocio_email) }}" placeholder="info@negocio.com">
                        </div>
                        <div>
                            <label>Ciudad</label>
                            <input type="text" name="ciudad" value="{{ old('ciudad', $user->ciudad) }}" placeholder="Madrid">
                        </div>
                        <div style="grid-column:span 2;">
                            <label>Dirección</label>
                            <input type="text" name="direccion" value="{{ old('direccion', $user->direccion) }}" placeholder="Calle Mayor 1, 28001 Madrid">
                        </div>
                        <div style="grid-column:span 2;">
                            <label>Descripción</label>
                            <textarea name="descripcion_negocio" rows="4" placeholder="Describe tu negocio...">{{ old('descripcion_negocio', $user->descripcion_negocio) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card" style="padding:28px; margin-bottom:20px;">
                    <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:20px;">🕐 Horario de apertura</h3>
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="padding:10px 12px; text-align:left; font-size:12px; font-weight:600; color:#64748b; border-bottom:1px solid #e2e8f0;">Día</th>
                                <th style="padding:10px 12px; text-align:center; font-size:12px; font-weight:600; color:#64748b; border-bottom:1px solid #e2e8f0;">Abierto</th>
                                <th style="padding:10px 12px; text-align:left; font-size:12px; font-weight:600; color:#64748b; border-bottom:1px solid #e2e8f0;">Apertura</th>
                                <th style="padding:10px 12px; text-align:left; font-size:12px; font-weight:600; color:#64748b; border-bottom:1px solid #e2e8f0;">Cierre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\HorarioNegocio::DIAS as $i => $dia)
                                @php $h = $horarios[$i] ?? null; @endphp
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    <td style="padding:12px; font-size:14px; font-weight:500; color:#0f172a;">{{ $dia }}</td>
                                    <td style="padding:12px; text-align:center;">
                                        <input type="checkbox" name="horario_activo_{{ $i }}" value="1"
                                            {{ $h && $h->activo ? 'checked' : '' }}
                                            onchange="toggleDia({{ $i }}, this.checked)"
                                            style="width:18px; height:18px; cursor:pointer;">
                                    </td>
                                    <td style="padding:12px;">
                                        <input type="time" name="hora_apertura_{{ $i }}"
                                            value="{{ $h ? $h->hora_apertura : '09:00' }}"
                                            id="apertura-{{ $i }}"
                                            {{ $h && !$h->activo ? 'disabled' : '' }}
                                            style="width:130px; {{ $h && !$h->activo ? 'opacity:0.4;' : '' }}">
                                    </td>
                                    <td style="padding:12px;">
                                        <input type="time" name="hora_cierre_{{ $i }}"
                                            value="{{ $h ? $h->hora_cierre : '20:00' }}"
                                            id="cierre-{{ $i }}"
                                            {{ $h && !$h->activo ? 'disabled' : '' }}
                                            style="width:130px; {{ $h && !$h->activo ? 'opacity:0.4;' : '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card" style="padding:28px; margin-bottom:20px;">
                    <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:20px;">📱 Redes sociales</h3>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div>
                            <label>Instagram</label>
                            <div style="display:flex; align-items:center; gap:8px; border:1px solid #e2e8f0; border-radius:8px; padding:0 12px; background:white;">
                                <span style="font-size:13px; color:#94a3b8;">@</span>
                                <input type="text" name="instagram" value="{{ old('instagram', $user->instagram) }}" placeholder="minegocio" style="border:none; padding:10px 0; box-shadow:none;">
                            </div>
                        </div>
                        <div>
                            <label>Facebook</label>
                            <input type="text" name="facebook" value="{{ old('facebook', $user->facebook) }}" placeholder="https://facebook.com/minegocio">
                        </div>
                        <div>
                            <label>WhatsApp de contacto</label>
                            <input type="text" name="whatsapp_negocio" value="{{ old('whatsapp_negocio', $user->whatsapp_negocio) }}" placeholder="+34 600 000 000">
                        </div>
                    </div>
                </div>

                <div style="text-align:right;">
                    <button type="submit" class="btn-primary">Guardar cambios</button>
                </div>
            </div>

            <!-- PÁGINA DE RESERVAS -->
            <div class="tab-content" id="content-reserva">
                <div class="card" style="padding:28px;">
                    <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:4px;">🔗 Página de reservas pública</h3>
                    @if($user->slug)
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px; padding:12px 16px; background:#f0f9ff; border-radius:8px; border:1px solid #bae6fd;">
                            <a href="{{ url('/reserva/' . $user->slug) }}" target="_blank" style="font-size:13px; color:#0369a1; font-weight:500; flex:1;">{{ url('/reserva/' . $user->slug) }}</a>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ url('/reserva/' . $user->slug) }}')" style="padding:4px 12px; border:1px solid #bae6fd; border-radius:6px; font-size:12px; color:#0369a1; background:white; cursor:pointer;">Copiar enlace</button>
                        </div>
                    @else
                        <p style="font-size:13px; color:#94a3b8; margin-bottom:24px;">Configura un slug para activar tu página pública.</p>
                    @endif
                    <div style="display:grid; grid-template-columns:1fr; gap:20px;">
                        <div>
                            <label>URL de tu página</label>
                            <div style="display:flex; align-items:center; border:1px solid #e2e8f0; border-radius:8px; overflow:hidden; background:white;">
                                <span style="font-size:13px; color:#94a3b8; padding:10px 12px; background:#f8fafc; border-right:1px solid #e2e8f0; white-space:nowrap;">app.timync.com/reserva/</span>
                                <input type="text" name="slug" value="{{ old('slug', $user->slug) }}" placeholder="mi-negocio" style="border:none; padding:10px 12px; flex:1;">
                            </div>
                        </div>
                        <div>
                            <label>Foto de portada / logo</label>
                            @if($user->foto_portada)
                                <div style="margin-bottom:12px;">
                                    <img src="{{ asset('storage/' . $user->foto_portada) }}" style="width:150px; height:100px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0;">
                                </div>
                            @endif
                            <input type="file" name="foto_portada" accept="image/*">
                        </div>
                        <div>
                            <label>Fotos de galería</label>
                            @if($user->fotos_galeria && count($user->fotos_galeria) > 0)
                                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
                                    @foreach($user->fotos_galeria as $foto)
                                        <div style="position:relative;">
                                            <img src="{{ asset('storage/' . $foto) }}" style="width:90px; height:65px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0;">
                                            <form action="{{ route('configuracion.eliminar-foto') }}" method="POST" style="position:absolute; top:-6px; right:-6px;">
                                                @csrf
                                                <input type="hidden" name="foto" value="{{ $foto }}">
                                                <button type="submit" style="width:20px; height:20px; border-radius:50%; background:#ef4444; color:white; border:none; cursor:pointer; font-size:10px;">✕</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="fotos_galeria[]" accept="image/*" multiple>
                            <div style="font-size:12px; color:#94a3b8; margin-top:4px;">Puedes subir varias fotos. La primera se usará como fondo del banner.</div>
                        </div>
                    </div>
                    <div style="margin-top:24px; text-align:right;">
                        <button type="submit" class="btn-primary">Guardar cambios</button>
                    </div>
                </div>
            </div>

            <!-- NOTIFICACIONES -->
            <div class="tab-content" id="content-notificaciones">
                <div class="card" style="padding:28px; margin-bottom:20px;">
                    <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:4px;">📧 Email (SMTP)</h3>
                    <p style="font-size:13px; color:#64748b; margin-bottom:20px;">Configura tu servidor de correo para enviar recordatorios y confirmaciones.</p>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div>
                            <label>Servidor SMTP</label>
                            <input type="text" name="mail_host" value="{{ old('mail_host', $setting->mail_host) }}" placeholder="smtp.hostinger.com">
                        </div>
                        <div>
                            <label>Puerto</label>
                            <input type="number" name="mail_port" value="{{ old('mail_port', $setting->mail_port) }}" placeholder="465">
                        </div>
                        <div>
                            <label>Usuario</label>
                            <input type="text" name="mail_username" value="{{ old('mail_username', $setting->mail_username) }}" placeholder="info@tudominio.com">
                        </div>
                        <div>
                            <label>Contraseña</label>
                            <input type="password" name="mail_password" placeholder="Dejar vacío para no cambiar">
                        </div>
                        <div>
                            <label>Email remitente</label>
                            <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $setting->mail_from_address) }}" placeholder="info@tudominio.com">
                        </div>
                        <div>
                            <label>Nombre remitente</label>
                            <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $setting->mail_from_name) }}" placeholder="Mi negocio">
                        </div>
                    </div>
                </div>

                <div class="card" style="padding:28px; margin-bottom:20px;">
                    <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:4px;">
                        <svg style="vertical-align:middle; margin-right:6px;" width="18" height="18" viewBox="0 0 24 24" fill="#229ED9"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-2.012 9.475c-.145.658-.537.818-1.084.508l-3-2.21-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.12L7.48 14.697l-2.95-.924c-.64-.203-.654-.64.136-.948l11.526-4.443c.537-.194 1.006.131.37.866z"/></svg>
                        Telegram Bot
                    </h3>
                    <p style="font-size:13px; color:#64748b; margin-bottom:20px;">Crea un bot en @BotFather para recibir notificaciones en Telegram.</p>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div>
                            <label>Bot Token</label>
                            <input type="text" name="telegram_bot_token" value="{{ old('telegram_bot_token', $setting->telegram_bot_token) }}" placeholder="123456:ABC-DEF...">
                        </div>
                        <div>
                            <label>Chat ID</label>
                            <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id', $setting->telegram_chat_id) }}" placeholder="Tu chat ID">
                        </div>
                    </div>
                </div>

                <div class="card" style="padding:28px; margin-bottom:20px;">
                    <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:4px;">
                        <svg style="vertical-align:middle; margin-right:6px;" width="18" height="18" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp API
                    </h3>
                    <p style="font-size:13px; color:#64748b; margin-bottom:20px;">Configura la API de Meta para enviar notificaciones por WhatsApp.</p>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div>
                            <label>Token</label>
                            <input type="text" name="whatsapp_token" value="{{ old('whatsapp_token', $setting->whatsapp_token) }}" placeholder="Token de Meta API">
                        </div>
                        <div>
                            <label>Phone ID</label>
                            <input type="text" name="whatsapp_phone_id" value="{{ old('whatsapp_phone_id', $setting->whatsapp_phone_id) }}" placeholder="Phone ID">
                        </div>
                    </div>
                </div>

                <div style="text-align:right;">
                    <button type="submit" class="btn-primary">Guardar cambios</button>
                </div>
            </div>

        </form>
    </div>

    <style>
        .tab-btn { display:flex; align-items:center; gap:10px; width:100%; padding:10px 14px; border:none; background:none; cursor:pointer; font-size:13px; font-weight:500; color:#64748b; border-radius:8px; text-align:left; transition:all 0.15s; }
        .tab-btn:hover { background:#f8fafc; color:#0f172a; }
        .tab-btn.active { background:#e8f0f9; color:#0f4c81; font-weight:600; }
        .tab-content { display:none; }
        .tab-content.active { display:block; }
    </style>

    <script>
        function mostrarTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById('tab-' + tab).classList.add('active');
            document.getElementById('content-' + tab).classList.add('active');
        }

        function toggleDia(i, activo) {
            const apertura = document.getElementById('apertura-' + i);
            const cierre   = document.getElementById('cierre-' + i);
            apertura.disabled = !activo;
            cierre.disabled   = !activo;
            apertura.style.opacity = activo ? '1' : '0.4';
            cierre.style.opacity   = activo ? '1' : '0.4';
        }

        const hash = window.location.hash.replace('#','');
        if (hash) mostrarTab(hash);
    </script>

</x-app-layout>
