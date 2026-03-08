<x-app-layout>
    <x-slot name="header">Editar empleado</x-slot>

    <div class="card" style="padding:32px; max-width:640px;">

        @if($errors->any())
            <div class="alert-error" style="margin-bottom:24px;">
                <ul style="margin:0; padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('empleados.update', $empleado) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div style="grid-column:span 2;">
                    <label>Nombre completo *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $empleado->nombre) }}" placeholder="Ej: María García">
                </div>

                <div>
                    <label>Email *</label>
                    <input type="email" name="email" value="{{ old('email', $empleado->email) }}" placeholder="maria@empresa.com">
                </div>

                <div>
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $empleado->telefono) }}" placeholder="+34 600 000 000">
                </div>

                <div style="grid-column:span 2;">
                    <label>Especialidad</label>
                    <input type="text" name="especialidad" value="{{ old('especialidad', $empleado->especialidad) }}" placeholder="Ej: Fisioterapia, Peluquería...">
                </div>

                <div style="grid-column:span 2;">
                    <label>Estado</label>
                    <select name="activo">
                        <option value="1" {{ $empleado->activo ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !$empleado->activo ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:28px;">
                <button type="submit" class="btn-primary">Actualizar empleado</button>
                <a href="{{ route('empleados.index') }}" style="padding:8px 18px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500;">Cancelar</a>
            </div>
        </form>
    </div>

</x-app-layout>
