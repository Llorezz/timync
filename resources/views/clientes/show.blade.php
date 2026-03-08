<x-app-layout>
    <x-slot name="header">Ficha de cliente</x-slot>

    <div style="display:grid; grid-template-columns:1fr 2fr; gap:24px;">

        <!-- Datos del cliente -->
        <div class="card" style="padding:24px; height:fit-content;">
            <div style="text-align:center; margin-bottom:20px;">
                <div style="width:64px; height:64px; background:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 12px; color:white; font-size:24px; font-weight:700;">
                    {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
                </div>
                <h2 style="font-size:18px; font-weight:700; color:#0f172a;">{{ $cliente->nombre }}</h2>
            </div>

            <div style="border-top:1px solid #f1f5f9; padding-top:16px;">
                <div style="margin-bottom:12px;">
                    <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Email</div>
                    <div style="font-size:14px; color:#334155; margin-top:2px;">{{ $cliente->email ?? '—' }}</div>
                </div>
                <div style="margin-bottom:12px;">
                    <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Teléfono</div>
                    <div style="font-size:14px; color:#334155; margin-top:2px;">{{ $cliente->telefono ?? '—' }}</div>
                </div>
                <div style="margin-bottom:12px;">
                    <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Cliente desde</div>
                    <div style="font-size:14px; color:#334155; margin-top:2px;">{{ $cliente->created_at->format('d/m/Y') }}</div>
                </div>
                @if($cliente->notas)
                <div style="margin-bottom:12px;">
                    <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Notas</div>
                    <div style="font-size:14px; color:#334155; margin-top:2px;">{{ $cliente->notas }}</div>
                </div>
                @endif
            </div>

            <div style="margin-top:20px; display:flex; flex-direction:column; gap:8px;">
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn-primary" style="justify-content:center;">Editar cliente</a>
                <a href="{{ route('citas.create') }}?cliente_id={{ $cliente->id }}" style="padding:8px 18px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500; text-align:center;">+ Nueva cita</a>
            </div>
        </div>

        <!-- Historial de citas -->
        <div class="card" style="padding:24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h3 style="font-size:16px; font-weight:600; color:#0f172a;">Historial de citas</h3>
                <span class="badge" style="background:#e0f2fe; color:#0369a1;">{{ $citas->count() }} total</span>
            </div>

            @if($citas->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Empleado</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($citas as $cita)
                    <tr>
                        <td class="mono" style="font-size:13px;">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y H:i') }}</td>
                        <td>{{ $cita->empleado->nombre }}</td>
                        <td style="color:#64748b;">{{ $cita->servicio->nombre }}</td>
                        <td><span class="badge badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td>
                        <td><a href="{{ route('citas.edit', $cita) }}" class="btn-edit">Editar</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center; padding:40px; color:#94a3b8;">
                <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block; opacity:0.4;"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                Este cliente no tiene citas aún.
            </div>
            @endif
        </div>
    </div>

</x-app-layout>
