<x-app-layout>
    <x-slot name="header">Automatizaciones</x-slot>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:24px;">

        <!-- Reglas activas -->
        <div class="card" style="padding:24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <div>
                    <h2 style="font-size:16px; font-weight:600; color:#0f172a;">Reglas configuradas</h2>
                    <p style="color:#64748b; font-size:13px; margin-top:2px;">El sistema trabaja solo</p>
                </div>
                <a href="{{ route('automatizaciones.create') }}" class="btn-primary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    Nueva regla
                </a>
            </div>

            @if(session('success'))
                <div class="alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
            @endif

            @if($reglas->count() > 0)
                @foreach($reglas as $regla)
                <div style="border:1px solid #e2e8f0; border-radius:8px; padding:16px; margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:start;">
                        <div>
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                                <span style="font-size:14px; font-weight:600; color:#0f172a;">{{ $regla->nombre }}</span>
                                @if($regla->activo)
                                    <span class="badge" style="background:#d1fae5; color:#065f46;">Activa</span>
                                @else
                                    <span class="badge" style="background:#fee2e2; color:#991b1b;">Inactiva</span>
                                @endif
                            </div>
                            <div style="display:flex; gap:12px;">
                                <span style="font-size:12px; color:#64748b;">
                                    @switch($regla->tipo)
                                        @case('recordatorio') 📅 Recordatorio @break
                                        @case('no_show') 🚫 No-show @break
                                        @case('hueco_libre') 📢 Hueco libre @break
                                        @case('cliente_inactivo') 💤 Cliente inactivo @break
                                    @endswitch
                                </span>
                                <span style="font-size:12px; color:#64748b;">
                                    @switch($regla->canal)
                                        @case('email') 📧 Email @break
                                        @case('telegram') ✈️ Telegram @break
                                        @case('whatsapp') 💬 WhatsApp @break
                                    @endswitch
                                </span>
                                @if($regla->ultima_ejecucion)
                                    <span style="font-size:12px; color:#94a3b8;">Última vez: {{ $regla->ultima_ejecucion->format('d/m/Y H:i') }}</span>
                                @endif
                            </div>
                        </div>
                        <div style="display:flex; gap:12px;">
                            <a href="{{ route('automatizaciones.edit', $regla) }}" class="btn-edit">Editar</a>
                            <form action="{{ route('automatizaciones.destroy', $regla) }}" method="POST">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('¿Eliminar esta regla?')" class="btn-danger" style="background:none; border:none; cursor:pointer;">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align:center; padding:40px; color:#94a3b8;">
                    <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block; opacity:0.4;"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    No hay reglas. <a href="{{ route('automatizaciones.create') }}" style="color:var(--primary); font-weight:500;">Crea la primera</a>
                </div>
            @endif
        </div>

        <!-- Log de actividad -->
        <div class="card" style="padding:24px;">
            <h2 style="font-size:16px; font-weight:600; color:#0f172a; margin-bottom:20px;">Actividad reciente</h2>

            @if($logs->count() > 0)
                @foreach($logs as $log)
                <div style="border-bottom:1px solid #f1f5f9; padding:12px 0;">
                    <div style="display:flex; justify-content:space-between; align-items:start;">
                        <div>
                            <div style="font-size:13px; color:#334155; margin-bottom:4px;">{{ Str::limit($log->mensaje, 60) }}</div>
                            <div style="font-size:12px; color:#94a3b8;">{{ $log->created_at->format('d/m/Y H:i') }} · {{ $log->canal }}</div>
                        </div>
                        <span class="badge {{ $log->estado === 'enviado' ? 'badge-confirmada' : ($log->estado === 'fallido' ? 'badge-cancelada' : 'badge-pendiente') }}">
                            {{ ucfirst($log->estado) }}
                        </span>
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align:center; padding:40px; color:#94a3b8;">
                    No hay actividad aún.
                </div>
            @endif
        </div>

    </div>

</x-app-layout>
