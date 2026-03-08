<x-app-layout>
    <x-slot name="header">Detalle del negocio</x-slot>

    <div style="display:grid; grid-template-columns:1fr 2fr; gap:24px;">

        <!-- Datos del negocio -->
        <div class="card" style="padding:24px; height:fit-content;">
            <div style="text-align:center; margin-bottom:20px;">
                <div style="width:64px; height:64px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 12px; color:white; font-size:24px; font-weight:700;">
                    {{ strtoupper(substr($user->nombre_negocio ?? $user->name, 0, 1)) }}
                </div>
                <h2 style="font-size:18px; font-weight:700; color:#0f172a;">{{ $user->nombre_negocio ?? $user->name }}</h2>
                <div style="margin-top:6px;">
                    @if($user->activo)
                        <span class="badge badge-confirmada">Activo</span>
                    @else
                        <span class="badge badge-cancelada">Inactivo</span>
                    @endif
                </div>
            </div>

            <div style="border-top:1px solid #f1f5f9; padding-top:16px;">
                <div style="margin-bottom:12px;">
                    <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase;">Nombre</div>
                    <div style="font-size:14px; color:#334155; margin-top:2px;">{{ $user->name }}</div>
                </div>
                <div style="margin-bottom:12px;">
                    <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase;">Email</div>
                    <div style="font-size:14px; color:#334155; margin-top:2px;">{{ $user->email }}</div>
                </div>
                <div style="margin-bottom:12px;">
                    <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase;">Teléfono</div>
                    <div style="font-size:14px; color:#334155; margin-top:2px;">{{ $user->telefono_negocio ?? '—' }}</div>
                </div>
                <div style="margin-bottom:12px;">
                    <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase;">Registrado</div>
                    <div style="font-size:14px; color:#334155; margin-top:2px;">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div style="margin-top:20px; display:flex; flex-direction:column; gap:8px;">
                <form action="{{ route('admin.toggle', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary" style="width:100%; justify-content:center; background:{{ $user->activo ? '#f59e0b' : '#10b981' }};">
                        {{ $user->activo ? 'Desactivar negocio' : 'Activar negocio' }}
                    </button>
                </form>
                <form action="{{ route('admin.destroy', $user) }}" method="POST">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('¿Eliminar este negocio y todos sus datos?')" style="width:100%; padding:8px 18px; border:1px solid #fecaca; border-radius:8px; font-size:14px; color:#dc2626; font-weight:500; cursor:pointer; background:none;">
                        Eliminar negocio
                    </button>
                </form>
                <a href="{{ route('admin.index') }}" style="padding:8px 18px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500; text-align:center;">Volver</a>
            </div>
        </div>

        <!-- Estadísticas del negocio -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; height:fit-content;">
            <div class="metric-card" style="border-top-color:#0f4c81;">
                <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase;">Total citas</div>
                <div style="font-size:28px; font-weight:700; color:#0f172a; margin-top:8px;" class="mono">{{ $citas }}</div>
            </div>
            <div class="metric-card" style="border-top-color:#10b981;">
                <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase;">Total clientes</div>
                <div style="font-size:28px; font-weight:700; color:#0f172a; margin-top:8px;" class="mono">{{ $clientes }}</div>
            </div>
        </div>

    </div>

</x-app-layout>
