<x-app-layout>
    <x-slot name="header">Empleados</x-slot>

    <div class="card" style="padding:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <h2 style="font-size:16px; font-weight:600; color:#0f172a;">Lista de empleados</h2>
                <p style="color:#64748b; font-size:13px; margin-top:2px;">Gestiona tu equipo de trabajo</p>
            </div>
            <a href="{{ route('empleados.create') }}" class="btn-primary">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Nuevo empleado
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
        @endif

        @if($empleados->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Especialidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleados as $empleado)
                <tr>
                    <td style="font-weight:500; color:#0f172a;">{{ $empleado->nombre }}</td>
                    <td style="color:#64748b;">{{ $empleado->email }}</td>
                    <td style="color:#64748b;">{{ $empleado->telefono ?? '—' }}</td>
                    <td>{{ $empleado->especialidad ?? '—' }}</td>
                    <td>
                        @if($empleado->activo)
                            <span class="badge" style="background:#d1fae5; color:#065f46;">Activo</span>
                        @else
                            <span class="badge" style="background:#fee2e2; color:#991b1b;">Inactivo</span>
                        @endif
                    </td>
                    <td style="display:flex; gap:16px; align-items:center;">
<a href="{{ route('empleados.horarios', $empleado) }}" style="color:#8b5cf6; font-size:14px; font-weight:500;">Horarios</a>
                        <a href="{{ route('empleados.edit', $empleado) }}" class="btn-edit">Editar</a>
                        <form action="{{ route('empleados.destroy', $empleado) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('¿Eliminar este empleado?')" class="btn-danger" style="background:none; border:none; cursor:pointer;">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:20px;">{{ $empleados->links() }}</div>
        @else
        <div style="text-align:center; padding:40px; color:#94a3b8;">
            <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24" style="margin:0 auto 12px; display:block; opacity:0.4;"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            No hay empleados. <a href="{{ route('empleados.create') }}" style="color:var(--primary); font-weight:500;">Añade el primero</a>
        </div>
        @endif
    </div>

</x-app-layout>
