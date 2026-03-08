<x-app-layout>
    <x-slot name="header">Citas</x-slot>

    <style>
        .filtros-wrap{background:white;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);padding:20px;margin-bottom:20px;}
        .filtros-grid{display:grid;grid-template-columns:1fr 1fr 1fr 1fr auto;gap:12px;align-items:end;}
        .f-label{font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.04em;display:block;margin-bottom:6px;}
        .f-input{width:100%;padding:9px 12px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:13px;color:#334155;background:#fafafa;outline:none;}
        .f-input:focus{border-color:#0f4c81;background:white;}
        .cita-row{display:flex;align-items:center;padding:14px 16px;border-radius:12px;border:1px solid #f1f5f9;margin-bottom:8px;gap:16px;transition:all 0.15s;background:white;}
        .cita-row:hover{border-color:#cbd5e1;box-shadow:0 2px 8px rgba(0,0,0,0.06);}
        .cita-fecha-box{background:#f0f7ff;border-radius:10px;padding:8px 12px;text-align:center;min-width:56px;flex-shrink:0;}
        .cita-fecha-dia{font-size:22px;font-weight:800;color:#0f4c81;line-height:1;}
        .cita-fecha-mes{font-size:10px;font-weight:600;color:#64748b;text-transform:uppercase;margin-top:2px;}
        .cita-hora{font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;}
        .cita-hora-fin{font-size:12px;color:#94a3b8;}
        .cita-info{flex:1;min-width:0;}
        .cita-cliente{font-size:14px;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .cita-sub{font-size:12px;color:#64748b;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;}
        .badge-pendiente{background:#fef9c3;color:#854d0e;}
        .badge-confirmada{background:#dcfce7;color:#166534;}
        .badge-cancelada{background:#fee2e2;color:#991b1b;}
        .cita-actions{display:flex;gap:8px;flex-shrink:0;}
        .btn-accion{padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;border:none;transition:all 0.15s;}
        .btn-editar{background:#e8f0f9;color:#0f4c81;}
        .btn-editar:hover{background:#0f4c81;color:white;}
        .btn-eliminar{background:#fee2e2;color:#dc2626;}
        .btn-eliminar:hover{background:#dc2626;color:white;}
        @media(max-width:767px){
            .filtros-grid{grid-template-columns:1fr 1fr;}
            .cita-row{flex-wrap:wrap;gap:10px;}
        }
    </style>

    {{-- FILTROS --}}
    <div class="filtros-wrap">
        <form method="GET" action="{{ route('citas.index') }}">
            <div class="filtros-grid">
                <div>
                    <label class="f-label">Desde</label>
                    <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Hasta</label>
                    <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Estado</label>
                    <select name="estado" class="f-input">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado')=='pendiente'?'selected':'' }}>Pendiente</option>
                        <option value="confirmada" {{ request('estado')=='confirmada'?'selected':'' }}>Confirmada</option>
                        <option value="cancelada" {{ request('estado')=='cancelada'?'selected':'' }}>Cancelada</option>
                    </select>
                </div>
                <div>
                    <label class="f-label">Cliente</label>
                    <select name="cliente_id" class="f-input">
                        <option value="">Todos</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ request('cliente_id')==$cliente->id?'selected':'' }}>{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                @if($empleados->count() > 0)
                <div>
                    <label class="f-label">Empleado</label>
                    <select name="empleado_id" class="f-input">
                        <option value="">Todos</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}" {{ request('empleado_id')==$empleado->id?'selected':'' }}>{{ $empleado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div style="display:flex;gap:8px;align-items:flex-end;">
                    <button type="submit" style="padding:9px 18px;background:linear-gradient(135deg,#0f4c81,#1a6eb5);color:white;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;">
                        🔍 Filtrar
                    </button>
                    <a href="{{ route('citas.index') }}" style="padding:9px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:13px;color:#64748b;font-weight:500;text-decoration:none;white-space:nowrap;">
                        ✕ Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- LISTA --}}
    <div style="background:white;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);padding:24px;">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
            <div>
                <h2 style="font-size:16px;font-weight:700;color:#0f172a;">Lista de citas</h2>
                <p style="color:#94a3b8;font-size:13px;margin-top:2px;">{{ $citas->total() }} citas encontradas</p>
            </div>
            <div style="display:flex;gap:10px;">
                <a href="{{ route('citas.calendario') }}" style="padding:9px 16px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:13px;color:#64748b;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:6px;">
                    📅 Calendario
                </a>
                <a href="{{ route('citas.create') }}" style="padding:9px 16px;background:linear-gradient(135deg,#0f4c81,#1a6eb5);color:white;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:6px;">
                    ＋ Nueva cita
                </a>
            </div>
        </div>

        @if(session('success'))
        <div style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:16px;color:#166534;font-size:13px;font-weight:500;">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if($citas->count() > 0)

        {{-- Agrupar por fecha --}}
        @php
            $citasAgrupadas = $citas->groupBy(fn($c) => \Carbon\Carbon::parse($c->fecha_hora)->format('Y-m-d'));
            $meses = ['01'=>'Ene','02'=>'Feb','03'=>'Mar','04'=>'Abr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Ago','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dic'];
            $dias  = ['Monday'=>'Lunes','Tuesday'=>'Martes','Wednesday'=>'Miércoles','Thursday'=>'Jueves','Friday'=>'Viernes','Saturday'=>'Sábado','Sunday'=>'Domingo'];
        @endphp

        @foreach($citasAgrupadas as $fecha => $grupoCitas)
        @php
            $carbon = \Carbon\Carbon::parse($fecha);
            $diaNombre = $dias[$carbon->englishDayOfWeek] ?? $carbon->englishDayOfWeek;
            $esHoy = $carbon->isToday();
            $esManana = $carbon->isTomorrow();
        @endphp
        <div style="margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                <div style="font-size:12px;font-weight:700;color:{{ $esHoy ? '#0f4c81' : '#94a3b8' }};text-transform:uppercase;letter-spacing:0.06em;">
                    @if($esHoy) 🟢 Hoy
                    @elseif($esManana) 🟡 Mañana
                    @else {{ $diaNombre }}
                    @endif
                    — {{ $carbon->format('d') }} {{ $meses[$carbon->format('m')] }} {{ $carbon->format('Y') }}
                </div>
                <div style="flex:1;height:1px;background:#f1f5f9;"></div>
                <div style="font-size:11px;color:#cbd5e1;font-weight:600;">{{ $grupoCitas->count() }} cita{{ $grupoCitas->count()>1?'s':'' }}</div>
            </div>

            @foreach($grupoCitas as $cita)
            @php $ch = \Carbon\Carbon::parse($cita->fecha_hora); @endphp
            <div class="cita-row">
                {{-- Caja día --}}
                <div class="cita-fecha-box">
                    <div class="cita-fecha-dia">{{ $ch->format('d') }}</div>
                    <div class="cita-fecha-mes">{{ $meses[$ch->format('m')] }}</div>
                </div>
                {{-- Hora --}}
                <div style="text-align:center;flex-shrink:0;min-width:50px;">
                    <div class="cita-hora">{{ $ch->format('H:i') }}</div>
                    @if($cita->fecha_fin)
                    <div class="cita-hora-fin">{{ \Carbon\Carbon::parse($cita->fecha_fin)->format('H:i') }}</div>
                    @endif
                </div>
                {{-- Separador --}}
                <div style="width:1px;height:36px;background:#f1f5f9;flex-shrink:0;"></div>
                {{-- Info --}}
                <div class="cita-info">
                    <div class="cita-cliente">
                        @if($cita->cliente)
                            <a href="{{ route('clientes.show', $cita->cliente) }}" style="color:#0f172a;text-decoration:none;">{{ $cita->cliente->nombre }}</a>
                        @else
                            <span style="color:#94a3b8;font-style:italic;">Sin cliente</span>
                        @endif
                    </div>
                    <div class="cita-sub">
                        {{ $cita->servicio->nombre }}
                        @if($empleados->count() > 0 && $cita->empleado)
                         · {{ $cita->empleado->nombre ?? '—' }}
                        @endif
                        @if($cita->notas)
                         · 📝 {{ Str::limit($cita->notas, 40) }}
                        @endif
                    </div>
                </div>
                {{-- Badge --}}
                <span class="badge badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span>
                {{-- Acciones --}}
                <div class="cita-actions">
                    <a href="{{ route('citas.edit', $cita) }}" class="btn-accion btn-editar">Editar</a>
                    <form action="{{ route('citas.destroy', $cita) }}" method="POST" style="margin:0;">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('¿Eliminar esta cita?')" class="btn-accion btn-eliminar">Eliminar</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach

        <div style="margin-top:16px;">{{ $citas->links() }}</div>

        @else
        <div style="text-align:center;padding:60px 20px;color:#94a3b8;">
            <div style="font-size:48px;margin-bottom:12px;">📭</div>
            <div style="font-size:15px;font-weight:600;color:#64748b;margin-bottom:6px;">No hay citas</div>
            <div style="font-size:13px;margin-bottom:20px;">No se encontraron citas con esos filtros.</div>
            <a href="{{ route('citas.index') }}" style="color:#0f4c81;font-weight:600;font-size:13px;">Ver todas las citas →</a>
        </div>
        @endif
    </div>

</x-app-layout>
