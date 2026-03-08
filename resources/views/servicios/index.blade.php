<x-app-layout>
    <x-slot name="header">Servicios</x-slot>

    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <h2 style="font-size:16px; font-weight:600; color:#0f172a;">Lista de servicios</h2>
                <p style="color:#64748b; font-size:13px; margin-top:2px;">Gestiona los servicios que ofreces</p>
            </div>
            <a href="{{ route('servicios.create') }}" class="btn-primary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Nuevo servicio
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
        @endif

        @if($servicios->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Duración</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios as $servicio)
                <tr>
                    <td style="font-weight:500; color:#0f172a;">{{ $servicio->nombre }}</td>
                    <td style="color:#64748b;">{{ $servicio->descripcion ?? '—' }}</td>
                    <td class="mono" style="font-weight:600; color:#0f172a;">{{ number_format($servicio->precio, 2) }}€</td>
                    <td style="color:#64748b;">{{ $servicio->duracion_minutos }} min</td>
                    <td>
                        @if($servicio->activo)
                            <span class="badge" style="background:#d1fae5; color:#065f46;">Activo</span>
                        @else
                            <span class="badge" style="background:#fee2e2; color:#991b1b;">Inactivo</span>
                        @endif
                    </td>
                    <td style="display:flex; gap:16px; align-items:center;">
                        <a href="{{ route('servicios.edit', $servicio) }}" class="btn-edit">Editar</a>
                        <form action="{{ route('servicios.destroy', $servicio) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('¿Eliminar este servicio?')" class="btn-danger" style="background:none; border:none; cursor:pointer;">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:20px;">{{ $servicios->links() }}</div>
        @else
        <div style="text-align:center; padding:40px; color:#94a3b8;">
            <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block; opacity:0.4;"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
            No hay servicios. <a href="{{ route('servicios.create') }}" style="color:var(--primary); font-weight:500;">Añade el primero</a>
        </div>
        @endif
    </div>

</x-app-layout>
