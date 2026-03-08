<x-app-layout>
    <x-slot name="header">Nuevo empleado</x-slot>

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

        <form action="{{ route('empleados.store') }}" method="POST">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div style="grid-column:span 2;">
                    <label>Nombre completo *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: María García">
                </div>

                <div>
                    <label>Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="maria@empresa.com">
                </div>

                <div>
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" placeholder="+34 600 000 000">
                </div>

                <div style="grid-column:span 2;">
                    <label>Especialidad</label>
                    <input type="text" name="especialidad" value="{{ old('especialidad') }}" placeholder="Ej: Fisioterapia, Peluquería...">
                </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:28px;">
                <button type="submit" class="btn-primary">Guardar empleado</button>
                <a href="{{ route('empleados.index') }}" style="padding:8px 18px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500;">Cancelar</a>
            </div>
        </form>
    </div>

</x-app-layout>
