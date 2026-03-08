<x-app-layout>
    <x-slot name="header">Nueva cita</x-slot>

    <div style="max-width:680px;">

        @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px 18px;margin-bottom:20px;display:flex;gap:10px;align-items:flex-start;">
            <span style="font-size:18px;">⚠️</span>
            <ul style="margin:0;padding-left:16px;color:#dc2626;font-size:13px;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <style>
            .cal-wrap{border-radius:14px;overflow:hidden;box-shadow:0 2px 12px rgba(15,76,129,0.1);}
            .cal-header{background:linear-gradient(135deg,#0f4c81,#1a6eb5);padding:14px 12px;display:flex;align-items:center;justify-content:space-between;}
            .cal-title{color:white;font-size:15px;font-weight:700;flex:1;text-align:center;}
            .cal-btn{background:rgba(255,255,255,0.2);border:none;color:white;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:20px;line-height:1;display:flex;align-items:center;justify-content:center;}
            .cal-btn:hover{background:rgba(255,255,255,0.35);}
            .cal-weekdays{background:linear-gradient(135deg,#0f4c81,#1a6eb5);display:grid;grid-template-columns:repeat(7,1fr);padding:0 8px 10px;}
            .cal-wd{color:rgba(255,255,255,0.85);font-size:11px;font-weight:600;text-align:center;padding:2px 0;}
            .cal-days{background:white;display:grid;grid-template-columns:repeat(7,1fr);padding:8px;gap:3px;}
            .cal-day{height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:500;cursor:pointer;position:relative;border:1.5px solid transparent;transition:all 0.15s;color:#334155;user-select:none;}
            .cal-day:hover:not(.cd-dis):not(.cd-empty){border-color:#0f4c81;color:#0f4c81;background:#e8f0f9;}
            .cd-sel{background:#0f4c81!important;color:white!important;border-color:#0f4c81!important;font-weight:700!important;}
            .cd-today{border-color:#0f4c81;color:#0f4c81;font-weight:700;}
            .cd-dis{color:#cbd5e1;cursor:default;opacity:0.5;}
            .cd-empty{cursor:default;}
            .slot-btn{padding:9px 4px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;cursor:pointer;background:white;text-align:center;transition:all 0.15s;font-weight:500;width:100%;color:#334155;}
            .slot-btn:hover{border-color:#0f4c81;color:#0f4c81;background:#e8f0f9;}
            .slot-btn.sel{background:#0f4c81;color:white;border-color:#0f4c81;font-weight:700;}
            select:focus, input:focus, textarea:focus{border-color:#0f4c81!important;background:white!important;box-shadow:0 0 0 3px rgba(15,76,129,0.08);}
        </style>

        <form action="{{ route('citas.store') }}" method="POST" id="form-cita">
            @csrf
            <input type="hidden" name="fecha_hora" id="input-fecha-hora">

            {{-- CLIENTE --}}
            <div style="background:white;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);padding:24px;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                    <div style="width:36px;height:36px;background:#e8f0f9;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;">👤</div>
                    <div>
                        <div style="font-weight:700;font-size:14px;color:#0f172a;">Cliente</div>
                        <div style="font-size:12px;color:#94a3b8;">Opcional — puedes dejarlo sin asignar</div>
                    </div>
                </div>
                <select name="cliente_id" style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:#fafafa;outline:none;cursor:pointer;">
                    <option value="">— Sin cliente asignado —</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id', request('cliente_id')) == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }}{{ $cliente->telefono ? ' · '.$cliente->telefono : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- EMPLEADO Y SERVICIO --}}
            <div style="background:white;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);padding:24px;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                    <div style="width:36px;height:36px;background:#e8f0f9;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;">✂️</div>
                    <div>
                        <div style="font-weight:700;font-size:14px;color:#0f172a;">Servicio y profesional</div>
                        <div style="font-size:12px;color:#94a3b8;">Selecciona quién realizará el servicio</div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div>
                        <label style="font-size:12px;font-weight:600;color:#64748b;display:block;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.04em;">Empleado *</label>
                        <select name="empleado_id" style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:#fafafa;outline:none;cursor:pointer;">
                            <option value="">— Selecciona —</option>
                            @foreach($empleados as $empleado)
                                <option value="{{ $empleado->id }}" {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:600;color:#64748b;display:block;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.04em;">Servicio *</label>
                        <select name="servicio_id" id="sel-servicio" style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:#fafafa;outline:none;cursor:pointer;">
                            <option value="">— Selecciona —</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->id }}"
                                    data-precio="{{ $servicio->precio }}"
                                    data-duracion="{{ $servicio->duracion_minutos }}"
                                    {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                                    {{ $servicio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="servicio-info" style="display:none;margin-top:14px;background:#f0f7ff;border-radius:10px;padding:12px 16px;gap:20px;align-items:center;">
                    <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#0f4c81;">⏱ <span id="info-duracion" style="font-weight:600;"></span></div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:#0f4c81;">💶 <span id="info-precio" style="font-weight:600;"></span></div>
                </div>
            </div>

            {{-- FECHA --}}
            <div style="background:white;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);padding:24px;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                    <div style="width:36px;height:36px;background:#e8f0f9;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;">📅</div>
                    <div>
                        <div style="font-weight:700;font-size:14px;color:#0f172a;">Fecha</div>
                        <div style="font-size:12px;color:#94a3b8;">Selecciona el día de la cita</div>
                    </div>
                </div>
                <div class="cal-wrap">
                    <div class="cal-header">
                        <button type="button" class="cal-btn" onclick="calNavegar(-1)">&#8249;</button>
                        <div class="cal-title" id="cal-titulo"></div>
                        <button type="button" class="cal-btn" onclick="calNavegar(1)">&#8250;</button>
                    </div>
                    <div class="cal-weekdays">
                        <div class="cal-wd">Lun</div><div class="cal-wd">Mar</div><div class="cal-wd">Mié</div>
                        <div class="cal-wd">Jue</div><div class="cal-wd">Vie</div><div class="cal-wd">Sáb</div><div class="cal-wd">Dom</div>
                    </div>
                    <div class="cal-days" id="cal-days"></div>
                </div>
            </div>

            {{-- HORA --}}
            <div id="card-hora" style="display:none;background:white;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);padding:24px;margin-bottom:16px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                    <div style="width:36px;height:36px;background:#e8f0f9;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;">🕐</div>
                    <div>
                        <div style="font-weight:700;font-size:14px;color:#0f172a;">Hora</div>
                        <div style="font-size:12px;color:#94a3b8;" id="hora-subtitle">Selecciona una hora</div>
                    </div>
                </div>
                <div id="slots-loading" style="display:none;text-align:center;padding:16px;color:#94a3b8;font-size:13px;">⏳ Cargando horarios...</div>
                <div id="slots-empty" style="display:none;text-align:center;padding:20px;color:#94a3b8;font-size:13px;">😔 No hay horas disponibles este día.</div>
                <div id="slots-grid" style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;"></div>
            </div>

            {{-- NOTAS --}}
            <div style="background:white;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);padding:24px;margin-bottom:24px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;">
                    <div style="width:36px;height:36px;background:#e8f0f9;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;">📝</div>
                    <div>
                        <div style="font-weight:700;font-size:14px;color:#0f172a;">Notas</div>
                        <div style="font-size:12px;color:#94a3b8;">Información adicional</div>
                    </div>
                </div>
                <textarea name="notas" rows="3" placeholder="Petición especial, alergias, preferencias..."
                    style="width:100%;padding:11px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;color:#334155;background:#fafafa;outline:none;resize:vertical;font-family:inherit;">{{ old('notas') }}</textarea>
            </div>

            {{-- ACCIONES --}}
            <div style="display:flex;gap:12px;">
                <button type="submit" id="btn-guardar" disabled
                    style="flex:1;padding:14px;background:linear-gradient(135deg,#0f4c81,#1a6eb5);color:white;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;opacity:0.4;transition:opacity 0.2s;">
                    ✅ Guardar cita
                </button>
                <a href="{{ route('citas.index') }}"
                    style="padding:14px 24px;border:1.5px solid #e2e8f0;border-radius:12px;font-size:14px;color:#64748b;font-weight:600;text-decoration:none;display:flex;align-items:center;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
        const MESES=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        const SLUG='{{ auth()->user()->slug }}';
        let calYear=0,calMonth=0,fechaSel=null;

        function calNavegar(dir){
            calMonth+=dir;
            if(calMonth>11){calMonth=0;calYear++;}
            if(calMonth<0){calMonth=11;calYear--;}
            renderCal();
        }

        function renderCal(){
            const hoy=new Date();hoy.setHours(0,0,0,0);
            document.getElementById('cal-titulo').textContent=`${MESES[calMonth]} ${calYear}`;
            let primerDia=new Date(calYear,calMonth,1).getDay();
            primerDia=primerDia===0?6:primerDia-1;
            const ultimoDia=new Date(calYear,calMonth+1,0).getDate();
            const grid=document.getElementById('cal-days');
            grid.innerHTML='';
            for(let i=0;i<primerDia;i++){
                const e=document.createElement('div');e.className='cal-day cd-empty';grid.appendChild(e);
            }
            for(let n=1;n<=ultimoDia;n++){
                const fecha=new Date(calYear,calMonth,n);fecha.setHours(0,0,0,0);
                const key=`${calYear}-${String(calMonth+1).padStart(2,'0')}-${String(n).padStart(2,'0')}`;
                const d=document.createElement('div');
                d.textContent=n;
                let cls='cal-day';
                if(fecha<hoy){cls+=' cd-dis';}
                else{
                    if(fecha.toDateString()===hoy.toDateString())cls+=' cd-today';
                    if(fechaSel===key)cls+=' cd-sel';
                    d.onclick=()=>selFecha(key,d);
                }
                d.className=cls;
                grid.appendChild(d);
            }
        }

        function selFecha(key,elem){
            fechaSel=key;
            document.querySelectorAll('.cal-day').forEach(d=>d.classList.remove('cd-sel'));
            elem.classList.add('cd-sel');
            cargarSlots(key);
        }

        function cargarSlots(fecha){
            const servId=document.getElementById('sel-servicio').value;
            document.getElementById('card-hora').style.display='block';
            document.getElementById('slots-grid').innerHTML='';
            document.getElementById('slots-empty').style.display='none';
            document.getElementById('slots-loading').style.display='block';
            document.getElementById('input-fecha-hora').value='';
            document.getElementById('btn-guardar').disabled=true;
            document.getElementById('btn-guardar').style.opacity='0.4';
            const p=fecha.split('-');
            document.getElementById('hora-subtitle').textContent=`${p[2]}/${p[1]}/${p[0]}`;
            fetch(`/reserva/${SLUG}/disponibilidad?fecha=${fecha}&servicio_id=${servId}&empleado_id=`)
                .then(r=>r.json()).then(slots=>{
                    document.getElementById('slots-loading').style.display='none';
                    if(!slots.length){document.getElementById('slots-empty').style.display='block';return;}
                    const grid=document.getElementById('slots-grid');
                    slots.forEach(slot=>{
                        const btn=document.createElement('button');
                        btn.type='button';btn.className='slot-btn';btn.textContent=slot;
                        btn.onclick=()=>selSlot(btn,fecha,slot);
                        grid.appendChild(btn);
                    });
                });
        }

        function selSlot(btn,fecha,hora){
            document.querySelectorAll('.slot-btn').forEach(s=>s.classList.remove('sel'));
            btn.classList.add('sel');
            document.getElementById('input-fecha-hora').value=fecha+' '+hora+':00';
            document.getElementById('btn-guardar').disabled=false;
            document.getElementById('btn-guardar').style.opacity='1';
        }

        // Info servicio
        const sel=document.getElementById('sel-servicio');
        const info=document.getElementById('servicio-info');
        sel.addEventListener('change',function(){
            const opt=this.options[this.selectedIndex];
            if(this.value&&opt.dataset.precio){
                document.getElementById('info-duracion').textContent=opt.dataset.duracion+' min';
                document.getElementById('info-precio').textContent=parseFloat(opt.dataset.precio).toFixed(2)+'€';
                info.style.display='flex';
            }else{info.style.display='none';}
            if(fechaSel)cargarSlots(fechaSel);
        });
        if(sel.value)sel.dispatchEvent(new Event('change'));

        // Init calendario
        const hoy=new Date();
        calYear=hoy.getFullYear();calMonth=hoy.getMonth();
        renderCal();
    </script>
</x-app-layout>
