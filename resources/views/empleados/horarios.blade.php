<x-app-layout>
    <x-slot name="header">Horarios de {{ $empleado->nombre }}</x-slot>

    @if(session('success'))
        <div class="alert-success" style="margin-bottom:24px;">{{ session('success') }}</div>
    @endif

    <div style="display:grid; grid-template-columns:2fr 1fr; gap:24px;">

        <!-- Horario semanal -->
        <div class="card" style="padding:28px;">
            <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:20px;">🗓 Horario semanal</h3>

            <form action="{{ route('empleados.horarios.update', $empleado) }}" method="POST">
                @csrf
                @method('PUT')

                <table style="width:100%;">
                    <thead>
                        <tr>
                            <th style="text-align:left; padding:8px 12px; font-size:12px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Día</th>
                            <th style="text-align:center; padding:8px 12px; font-size:12px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Activo</th>
                            <th style="padding:8px 12px; font-size:12px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Mañana</th>
                            <th style="padding:8px 12px; font-size:12px; color:#94a3b8; font-weight:600; text-transform:uppercase;">Tarde</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($horarios as $horario)
                        <tr style="border-top:1px solid #f1f5f9;">
                            <td style="padding:12px; font-weight:500; color:#0f172a;">{{ $horario->nombre_dia }}</td>
                            <td style="padding:12px; text-align:center;">
                                <input type="checkbox" name="horarios[{{ $horario->id }}][activo]" value="1"
                                    {{ $horario->activo ? 'checked' : '' }}
                                    onchange="toggleDia({{ $horario->id }}, this.checked)"
                                    style="width:18px; height:18px; cursor:pointer;">
                            </td>
                            <td style="padding:12px;">
                                <div id="dia_{{ $horario->id }}" style="{{ !$horario->activo ? 'opacity:0.3; pointer-events:none;' : '' }}">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <input type="time" name="horarios[{{ $horario->id }}][hora_inicio_manana]"
                                            value="{{ $horario->hora_inicio_manana }}"
                                            style="width:110px; padding:6px 8px; font-size:13px;">
                                        <span style="color:#94a3b8;">—</span>
                                        <input type="time" name="horarios[{{ $horario->id }}][hora_fin_manana]"
                                            value="{{ $horario->hora_fin_manana }}"
                                            style="width:110px; padding:6px 8px; font-size:13px;">
                                    </div>
                                </div>
                            </td>
                            <td style="padding:12px;">
                                <div id="tarde_{{ $horario->id }}" style="{{ !$horario->activo ? 'opacity:0.3; pointer-events:none;' : '' }}">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <input type="time" name="horarios[{{ $horario->id }}][hora_inicio_tarde]"
                                            value="{{ $horario->hora_inicio_tarde }}"
                                            style="width:110px; padding:6px 8px; font-size:13px;">
                                        <span style="color:#94a3b8;">—</span>
                                        <input type="time" name="horarios[{{ $horario->id }}][hora_fin_tarde]"
                                            value="{{ $horario->hora_fin_tarde }}"
                                            style="width:110px; padding:6px 8px; font-size:13px;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:24px; display:flex; gap:12px;">
                    <button type="submit" class="btn-primary">Guardar horarios</button>
                    <a href="{{ route('empleados.index') }}" style="padding:8px 18px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px; color:#64748b; font-weight:500;">Volver</a>
                </div>
            </form>
        </div>

        <!-- Días libres -->
        <div>
            <div class="card" style="padding:24px; margin-bottom:20px;">
                <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:16px;">🏖 Añadir días libres</h3>
                <form action="{{ route('empleados.dias-libres.store', $empleado) }}" method="POST">
                    @csrf
                    <div style="margin-bottom:12px;">
                        <label>Desde</label>
                        <input type="date" name="fecha_inicio" required>
                    </div>
                    <div style="margin-bottom:12px;">
                        <label>Hasta</label>
                        <input type="date" name="fecha_fin" required>
                    </div>
                    <div style="margin-bottom:16px;">
                        <label>Motivo</label>
                        <input type="text" name="motivo" placeholder="Ej: Vacaciones, Baja...">
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%; justify-content:center;">Añadir</button>
                </form>
            </div>

            <!-- Lista días libres -->
            <div class="card" style="padding:24px;">
                <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:16px;">📋 Días libres programados</h3>
                @if($diasLibres->count() > 0)
                    @foreach($diasLibres as $diaLibre)
                    <div style="border:1px solid #e2e8f0; border-radius:8px; padding:12px; margin-bottom:8px;">
                        <div style="display:flex; justify-content:space-between; align-items:start;">
                            <div>
                                <div style="font-size:13px; font-weight:500; color:#0f172a;">
                                    {{ $diaLibre->fecha_inicio->format('d/m/Y') }}
                                    @if($diaLibre->fecha_inicio != $diaLibre->fecha_fin)
                                        → {{ $diaLibre->fecha_fin->format('d/m/Y') }}
                                    @endif
                                </div>
                                @if($diaLibre->motivo)
                                    <div style="font-size:12px; color:#64748b; margin-top:2px;">{{ $diaLibre->motivo }}</div>
                                @endif
                            </div>
                            <form action="{{ route('empleados.dias-libres.destroy', [$empleado, $diaLibre]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('¿Eliminar?')" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:12px;">Eliminar</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div style="text-align:center; padding:20px; color:#94a3b8; font-size:13px;">
                        No hay días libres programados
                    </div>
                @endif
            </div>
        </div>

    </div>

    <script>
        function toggleDia(id, activo) {
            const dia   = document.getElementById('dia_' + id);
            const tarde = document.getElementById('tarde_' + id);
            const style = activo ? '' : 'opacity:0.3; pointer-events:none;';
            if (dia) dia.style.cssText   = style;
            if (tarde) tarde.style.cssText = style;
        }
    </script>

</x-app-layout>
