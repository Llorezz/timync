<x-app-layout>
    <x-slot name="header">Editar cita</x-slot>

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

        <form action="{{ route('citas.update', $cita) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

                <div style="grid-column:span 2;">
                    <label>Cliente</label>
                    <select name="cliente_id">
                        <option value="">-- Sin cliente asignado --</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}"
                                {{ old('cliente_id', $cita->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }} {{ $cliente->telefono ? '· '.$cliente->telefono : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Empleado *</label>
                    <select name="empleado_id">
                        <option value="">-- Selecciona --</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}"
                                {{ old('empleado_id', $cita->empleado_id) == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Servicio *</label>
                    <select name="servicio_id">
                        <option value="">-- Selecciona --</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}"
                                {{ old('servicio_id', $cita->servicio_id) == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }} ({{ $servicio->duracion_minutos }} min · {{ number_format($servicio->precio, 2) }}€)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="grid-column:span 2;">
                    <label>Fecha y hora *</label>
                    <input type="datetime-local" name="fecha_hora"
                           value="{{ old('fecha_hora', date('Y-m-d\TH:i', strtotime($cita->fecha_hora))) }}">
                </div>

                <div style="grid-column:span 2;">
                    <label>Estado</label>
                    <select name="estado">
                        @foreach(['pendiente', 'confirmada', 'cancelada'] as $estado)
                            <option value="{{ $estado }}"
                                {{ old('estado', $cita->estado) == $estado ? 'selected' : '' }}>
                                {{ ucfirst($estado) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="grid-column:span 2;">
                    <label>Notas</label>
                    <textarea name="notas" rows="3" placeholder="Notas adicionales sobre la cita...">{{ old('notas', $cita->notas) }}</textarea>
                </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:28px;">
                <button type="submit" class="btn-primary">Actualizar cita</button>
                <a href="{{ route('citas.index') }}" style="padding:8px 18px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500;">Cancelar</a>
            </div>
        </form>
    </div>

</x-app-layout>
