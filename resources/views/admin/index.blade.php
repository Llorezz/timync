<x-app-layout>
    <x-slot name="header">Panel de Administración</x-slot>

    <!-- Métricas -->
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:24px;">
        <div class="metric-card" style="border-top-color:#0f4c81;">
            <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Negocios registrados</div>
            <div style="font-size:28px; font-weight:700; color:#0f172a; margin-top:8px;" class="mono">{{ $totalNegocios }}</div>
        </div>
        <div class="metric-card" style="border-top-color:#00b4d8;">
            <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Nuevos hoy</div>
            <div style="font-size:28px; font-weight:700; color:#0f172a; margin-top:8px;" class="mono">{{ $nuevosHoy }}</div>
        </div>
        <div class="metric-card" style="border-top-color:#10b981;">
            <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Total citas</div>
            <div style="font-size:28px; font-weight:700; color:#0f172a; margin-top:8px;" class="mono">{{ $totalCitas }}</div>
        </div>
        <div class="metric-card" style="border-top-color:#f59e0b;">
            <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Total clientes</div>
            <div style="font-size:28px; font-weight:700; color:#0f172a; margin-top:8px;" class="mono">{{ $totalClientes }}</div>
        </div>
    </div>

    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <h2 style="font-size:16px; font-weight:600; color:#0f172a;">Negocios registrados</h2>
                <p style="color:#64748b; font-size:13px; margin-top:2px;">Gestiona todos los negocios de la plataforma</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
        @endif

        @if($negocios->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Negocio</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Registrado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($negocios as $negocio)
                <tr>
                    <td>
                        <div style="font-weight:500; color:#0f172a;">{{ $negocio->nombre_negocio ?? $negocio->name }}</div>
                        <div style="font-size:12px; color:#94a3b8;">{{ $negocio->name }}</div>
                    </td>
                    <td style="color:#64748b;">{{ $negocio->email }}</td>
                    <td style="color:#64748b;">{{ $negocio->telefono_negocio ?? '—' }}</td>
                    <td style="color:#64748b; font-size:13px;">{{ $negocio->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($negocio->activo)
                            <span class="badge badge-confirmada">Activo</span>
                        @else
                            <span class="badge badge-cancelada">Inactivo</span>
                        @endif
                    </td>
                    <td style="display:flex; gap:12px; align-items:center;">
                        <a href="{{ route('admin.show', $negocio) }}" style="color:var(--primary); font-size:14px; font-weight:500;">Ver</a>
                        <form action="{{ route('admin.toggle', $negocio) }}" method="POST">
                            @csrf
                            <button type="submit" style="background:none; border:none; cursor:pointer; font-size:14px; font-weight:500; color:{{ $negocio->activo ? '#f59e0b' : '#10b981' }};">
                                {{ $negocio->activo ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.destroy', $negocio) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('¿Eliminar este negocio y todos sus datos?')" style="background:none; border:none; cursor:pointer; font-size:14px; font-weight:500; color:#ef4444;">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:20px;">{{ $negocios->links() }}</div>
        @else
        <div style="text-align:center; padding:40px; color:#94a3b8;">
            <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block; opacity:0.4;"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            No hay negocios registrados aún.
        </div>
        @endif
    </div>

</x-app-layout>
