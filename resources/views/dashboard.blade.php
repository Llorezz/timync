<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <!-- Métricas -->
    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:32px;">

        <div class="metric-card" style="padding:24px;">
            <div style="color:#64748b; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Citas hoy</div>
            <div style="font-size:36px; font-weight:700; color:#0f172a; margin:8px 0;">{{ $citasHoy }}</div>
            <div style="color:#64748b; font-size:13px;">programadas para hoy</div>
        </div>

        <div class="metric-card" style="padding:24px; border-top-color:#6366f1;">
            <div style="color:#64748b; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Próximas citas</div>
            <div style="font-size:36px; font-weight:700; color:#0f172a; margin:8px 0;">{{ $proximasCitas }}</div>
            <div style="color:#64748b; font-size:13px;">en los próximos 7 días</div>
        </div>

        <div class="metric-card" style="padding:24px; border-top-color:#10b981;">
            <div style="color:#64748b; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Empleados</div>
            <div style="font-size:36px; font-weight:700; color:#0f172a; margin:8px 0;">{{ $totalEmpleados }}</div>
            <div style="color:#64748b; font-size:13px;">activos en el sistema</div>
        </div>

        <div class="metric-card" style="padding:24px; border-top-color:#f59e0b;">
            <div style="color:#64748b; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">Ingresos del mes</div>
            <div style="font-size:36px; font-weight:700; color:#0f172a; margin:8px 0;">{{ number_format($ingresosMes, 2) }}€</div>
            <div style="color:#64748b; font-size:13px;">{{ now()->format('F Y') }}</div>
        </div>

    </div>

    <!-- Próximas citas -->
    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="font-size:16px; font-weight:600; color:#0f172a;">Próximas citas</h2>
            <a href="{{ route('citas.create') }}" class="btn-primary">+ Nueva cita</a>
        </div>

        @if($citas->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Fecha y hora</th>
                    <th>Empleado</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($citas as $cita)
                <tr>
                    <td class="mono" style="font-size:13px;">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}</td>
<td>{{ $cita->empleado->nombre ?? '—' }}</td>
                    <td>{{ $cita->servicio->nombre }}</td>
                    <td><span class="badge badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td>
                    <td>
                        <a href="{{ route('citas.edit', $cita) }}" class="btn-edit">Editar</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center; padding:40px; color:#94a3b8;">
            <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block; opacity:0.4;"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
            No hay citas programadas. <a href="{{ route('citas.create') }}" style="color:var(--primary); font-weight:500;">Crear una ahora</a>
        </div>
        @endif
    </div>

</x-app-layout>
