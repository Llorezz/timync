<x-app-layout>
    <x-slot name="header">Nuevo servicio</x-slot>

    <div class="card" style="padding:32px; max-width:700px;">

        @if($errors->any())
            <div class="alert-error" style="margin-bottom:24px;">
                <ul style="margin:0; padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('servicios.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                <div style="grid-column:span 2;">
                    <label>Nombre del servicio *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Corte de cabello">
                </div>

                <div>
                    <label>Precio (€) *</label>
                    <input type="number" name="precio" value="{{ old('precio') }}" step="0.01" min="0" placeholder="0.00">
                </div>

                <div>
                    <label>Duración (minutos) *</label>
                    <input type="number" name="duracion_minutos" value="{{ old('duracion_minutos', 30) }}" min="5" step="5">
                </div>

                <div style="grid-column:span 2;">
                    <label>Descripción corta</label>
                    <input type="text" name="descripcion" value="{{ old('descripcion') }}" placeholder="Breve descripción visible en la lista">
                </div>

                <div style="grid-column:span 2;">
                    <label>Descripción completa</label>
                    <textarea name="descripcion_larga" rows="4" placeholder="Descripción detallada del servicio para la página de reservas...">{{ old('descripcion_larga') }}</textarea>
                </div>

                @if($empleados->count() > 0)
                <div style="grid-column:span 2;">
                    <label>Empleado asignado</label>
                    <select name="empleado_id">
                        <option value="">Sin asignación (cualquiera)</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}" {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div style="grid-column:span 2;">
                    <label>Foto del servicio (opcional)</label>
                    <input type="file" name="foto" accept="image/*" onchange="previewFoto(this)">
                    <div id="foto-preview" style="margin-top:12px; display:none;">
                        <img id="foto-img" style="width:120px; height:80px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0;">
                    </div>
                </div>

            </div>

            <div style="display:flex; gap:12px; margin-top:28px;">
                <button type="submit" class="btn-primary">Guardar servicio</button>
                <a href="{{ route('servicios.index') }}" style="padding:8px 18px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500;">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('foto-img').src = e.target.result;
                    document.getElementById('foto-preview').style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</x-app-layout>
