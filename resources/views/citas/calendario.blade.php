<x-app-layout>
    <x-slot name="header">Calendario de citas</x-slot>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/es.global.min.js"></script>

    <style>
        :root{--primary:#0f4c81;--gradient:linear-gradient(135deg,#0f4c81,#1a6eb5);}

        /* TOOLBAR */
        .cal-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;}
        .cal-nav{display:flex;align-items:center;gap:8px;}
        .cal-nav-btn{width:36px;height:36px;border-radius:50%;border:1.5px solid #e2e8f0;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;color:#334155;transition:all 0.15s;}
        .cal-nav-btn:hover{background:#0f4c81;color:white;border-color:#0f4c81;}
        .cal-today-btn{padding:7px 16px;border-radius:20px;border:1.5px solid #e2e8f0;background:white;font-size:13px;font-weight:600;color:#334155;cursor:pointer;transition:all 0.15s;}
        .cal-today-btn:hover{background:#0f4c81;color:white;border-color:#0f4c81;}
        .cal-title-text{font-size:18px;font-weight:800;color:#0f172a;letter-spacing:-0.3px;}
        .cal-view-btns{display:flex;background:#f1f5f9;border-radius:10px;padding:3px;gap:2px;}
        .cal-view-btn{padding:6px 14px;border-radius:8px;border:none;font-size:12px;font-weight:600;cursor:pointer;color:#64748b;background:transparent;transition:all 0.15s;}
        .cal-view-btn.active{background:white;color:#0f4c81;box-shadow:0 1px 4px rgba(0,0,0,0.1);}
        .cal-actions{display:flex;gap:10px;}

        /* CALENDARIO */
        #cal-wrap{background:white;border-radius:16px;box-shadow:0 1px 4px rgba(0,0,0,0.06);overflow:hidden;}

        /* Cabecera días semana */
        .fc-col-header{background:var(--gradient)!important;}
        .fc-col-header-cell{border:none!important;}
        .fc-col-header-cell-cushion{color:white!important;font-size:12px!important;font-weight:600!important;text-transform:uppercase!important;letter-spacing:0.05em!important;padding:10px 0!important;text-decoration:none!important;}

        /* Días */
        .fc-daygrid-day-number{font-size:13px!important;font-weight:600!important;color:#64748b!important;text-decoration:none!important;padding:8px!important;}
        .fc-day-today{background:#f0f7ff!important;}
        .fc-day-today .fc-daygrid-day-number{color:#0f4c81!important;font-weight:800!important;}
        .fc-daygrid-day:hover{background:#fafcff!important;cursor:pointer;}

        /* Eventos */
        .fc-event{border-radius:6px!important;border:none!important;padding:2px 6px!important;font-size:12px!important;font-weight:600!important;margin-bottom:2px!important;cursor:pointer!important;}
        .fc-event:hover{filter:brightness(0.92)!important;}
        .fc-event-title{font-weight:600!important;}
        .fc-event-time{font-weight:700!important;margin-right:4px!important;}

        /* Vista semana */
        .fc-timegrid-slot{height:40px!important;}
        .fc-timegrid-slot-label{font-size:11px!important;color:#94a3b8!important;font-weight:500!important;}
        .fc-timegrid-event{border-radius:8px!important;border:none!important;padding:4px 6px!important;}

        /* Bordes */
        .fc-scrollgrid{border:none!important;}
        .fc-scrollgrid td,.fc-scrollgrid th{border-color:#f1f5f9!important;}
        .fc-theme-standard .fc-scrollgrid{border:none!important;}
        td.fc-day-other .fc-daygrid-day-number{color:#cbd5e1!important;}

        /* Ocultar botones nativos de FullCalendar */
        .fc-toolbar{display:none!important;}

        /* MODAL */
        #modal{display:none;position:fixed;inset:0;background:rgba(10,20,40,0.55);z-index:1000;align-items:center;justify-content:center;backdrop-filter:blur(4px);}
        #modal.open{display:flex;}
        #modal-box{background:white;border-radius:20px;width:100%;max-width:460px;box-shadow:0 20px 60px rgba(0,0,0,0.2);overflow:hidden;animation:slideUp 0.2s ease;}
        @keyframes slideUp{from{transform:translateY(20px);opacity:0;}to{transform:translateY(0);opacity:1;}}
        .modal-head{background:var(--gradient);padding:20px 24px;}
        .modal-head-title{color:white;font-size:16px;font-weight:700;}
        .modal-head-sub{color:rgba(255,255,255,0.75);font-size:12px;margin-top:4px;}
        .modal-body{padding:20px 24px;}
        .modal-row{display:flex;gap:10px;align-items:flex-start;padding:8px 0;border-bottom:1px solid #f8fafc;}
        .modal-row:last-child{border-bottom:none;}
        .modal-row-icon{width:28px;height:28px;background:#e8f0f9;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;margin-top:1px;}
        .modal-row-label{font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.04em;}
        .modal-row-value{font-size:13px;font-weight:500;color:#334155;margin-top:2px;}
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;}
        .badge-pendiente{background:#fef9c3;color:#854d0e;}
        .badge-confirmada{background:#dcfce7;color:#166534;}
        .badge-cancelada{background:#fee2e2;color:#991b1b;}
        .estado-btns{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:16px;}
        .estado-btn{padding:8px;border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;border:1.5px solid transparent;transition:all 0.15s;text-align:center;}
        .estado-btn-pendiente{background:#fef9c3;color:#854d0e;border-color:#fcd34d;}
        .estado-btn-pendiente:hover{background:#f59e0b;color:white;border-color:#f59e0b;}
        .estado-btn-confirmada{background:#dcfce7;color:#166534;border-color:#6ee7b7;}
        .estado-btn-confirmada:hover{background:#16a34a;color:white;border-color:#16a34a;}
        .estado-btn-cancelada{background:#fee2e2;color:#991b1b;border-color:#fca5a5;}
        .estado-btn-cancelada:hover{background:#dc2626;color:white;border-color:#dc2626;}
        .modal-footer{padding:16px 24px;border-top:1px solid #f1f5f9;display:flex;gap:10px;}
        .btn-modal-editar{flex:1;padding:10px;background:var(--gradient);color:white;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none;text-align:center;}
        .btn-modal-cerrar{flex:1;padding:10px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:13px;font-weight:600;color:#64748b;background:white;cursor:pointer;}
        .btn-modal-cerrar:hover{background:#f8fafc;}
    </style>

    {{-- TOOLBAR CUSTOM --}}
    <div class="cal-toolbar">
        <div class="cal-nav">
            <button class="cal-nav-btn" onclick="calNav(-1)">&#8249;</button>
            <button class="cal-nav-btn" onclick="calNav(1)">&#8250;</button>
            <button class="cal-today-btn" onclick="calHoy()">Hoy</button>
            <span class="cal-title-text" id="cal-title"></span>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <div class="cal-view-btns">
                <button class="cal-view-btn active" id="btn-mes" onclick="calVista('dayGridMonth')">Mes</button>
                <button class="cal-view-btn" id="btn-semana" onclick="calVista('timeGridWeek')">Semana</button>
                <button class="cal-view-btn" id="btn-dia" onclick="calVista('timeGridDay')">Día</button>
            </div>
            <div class="cal-actions">
                <a href="{{ route('citas.index') }}" style="padding:9px 16px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:13px;color:#64748b;font-weight:600;text-decoration:none;">
                    ☰ Listado
                </a>
                <a href="{{ route('citas.create') }}" style="padding:9px 16px;background:var(--gradient);color:white;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:6px;">
                    ＋ Nueva cita
                </a>
            </div>
        </div>
    </div>

    <div id="cal-wrap">
        <div id="calendario" style="padding:16px;"></div>
    </div>

    {{-- MODAL --}}
    <div id="modal">
        <div id="modal-box">
            <div class="modal-head">
                <div class="modal-head-title" id="modal-titulo">Detalle de la cita</div>
                <div class="modal-head-sub" id="modal-fecha-sub"></div>
            </div>
            <div class="modal-body">
                <div class="modal-row">
                    <div class="modal-row-icon">👤</div>
                    <div><div class="modal-row-label">Cliente</div><div class="modal-row-value" id="modal-cliente"></div></div>
                </div>
                <div class="modal-row">
                    <div class="modal-row-icon">✂️</div>
                    <div><div class="modal-row-label">Servicio</div><div class="modal-row-value" id="modal-servicio"></div></div>
                </div>
                <div class="modal-row">
                    <div class="modal-row-icon">👨‍💼</div>
                    <div><div class="modal-row-label">Empleado</div><div class="modal-row-value" id="modal-empleado"></div></div>
                </div>
                <div class="modal-row">
                    <div class="modal-row-icon">🕐</div>
                    <div><div class="modal-row-label">Horario</div><div class="modal-row-value" id="modal-horario"></div></div>
                </div>
                <div class="modal-row" id="modal-notas-row">
                    <div class="modal-row-icon">📝</div>
                    <div><div class="modal-row-label">Notas</div><div class="modal-row-value" id="modal-notas"></div></div>
                </div>
                <div class="modal-row">
                    <div class="modal-row-icon">🔖</div>
                    <div><div class="modal-row-label">Estado</div><div class="modal-row-value" id="modal-estado"></div></div>
                </div>
                <div style="margin-top:4px;">
                    <div style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:8px;">Cambiar estado</div>
                    <div class="estado-btns">
                        <button class="estado-btn estado-btn-pendiente" onclick="cambiarEstado('pendiente')">⏳ Pendiente</button>
                        <button class="estado-btn estado-btn-confirmada" onclick="cambiarEstado('confirmada')">✅ Confirmada</button>
                        <button class="estado-btn estado-btn-cancelada" onclick="cambiarEstado('cancelada')">❌ Cancelada</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a id="modal-editar" href="#" class="btn-modal-editar">✏️ Editar cita</a>
                <button onclick="cerrarModal()" class="btn-modal-cerrar">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        let cal, citaActualId=null;

        document.addEventListener('DOMContentLoaded', function(){
            cal = new FullCalendar.Calendar(document.getElementById('calendario'), {
                locale: 'es',
                initialView: 'dayGridMonth',
                headerToolbar: false,
                height: 'auto',
                events: '{{ route("citas.calendario.datos") }}',
                eventColor: '#0f4c81',
                eventDisplay: 'block',
                dayMaxEvents: 4,
                eventTimeFormat: { hour:'2-digit', minute:'2-digit', meridiem:false },
                eventClick: function(info){
                    const p = info.event.extendedProps;
                    citaActualId = info.event.id;
                    const start = info.event.start;
                    const end = info.event.end;
                    const fmt = d => d ? d.toLocaleTimeString('es',{hour:'2-digit',minute:'2-digit'}) : '';
                    const fmtFecha = d => d ? d.toLocaleDateString('es',{weekday:'long',day:'numeric',month:'long',year:'numeric'}) : '';
                    document.getElementById('modal-titulo').textContent = p.servicio;
                    document.getElementById('modal-fecha-sub').textContent = fmtFecha(start);
                    document.getElementById('modal-cliente').textContent = p.cliente || '—';
                    document.getElementById('modal-servicio').textContent = p.servicio;
                    document.getElementById('modal-empleado').textContent = p.empleado || '—';
                    document.getElementById('modal-horario').textContent = fmt(start) + (end ? ' → ' + fmt(end) : '');
                    document.getElementById('modal-notas').textContent = p.notas || '—';
                    document.getElementById('modal-editar').href = p.edit_url;
                    const badges = {pendiente:'badge-pendiente',confirmada:'badge-confirmada',cancelada:'badge-cancelada'};
                    const labels = {pendiente:'Pendiente',confirmada:'Confirmada',cancelada:'Cancelada'};
                    document.getElementById('modal-estado').innerHTML = `<span class="badge ${badges[p.estado]}">${labels[p.estado]}</span>`;
                    document.getElementById('modal').classList.add('open');
                },
                dateClick: function(info){
                    window.location.href = '{{ route("citas.create") }}?fecha=' + info.dateStr;
                },
                datesSet: function(){
                    actualizarTitulo();
                }
            });
            cal.render();
            actualizarTitulo();
        });

        function actualizarTitulo(){
            if(!cal) return;
            const fecha = cal.getDate();
            const meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
            const vista = cal.view.type;
            if(vista === 'dayGridMonth'){
                document.getElementById('cal-title').textContent = meses[fecha.getMonth()] + ' ' + fecha.getFullYear();
            } else if(vista === 'timeGridWeek'){
                document.getElementById('cal-title').textContent = 'Semana del ' + fecha.toLocaleDateString('es',{day:'numeric',month:'long'});
            } else {
                document.getElementById('cal-title').textContent = fecha.toLocaleDateString('es',{weekday:'long',day:'numeric',month:'long'});
            }
        }

        function calNav(dir){ cal.prev && (dir === -1 ? cal.prev() : cal.next()); actualizarTitulo(); }
        function calHoy(){ cal.today(); actualizarTitulo(); }
        function calVista(v){
            cal.changeView(v);
            actualizarTitulo();
            document.querySelectorAll('.cal-view-btn').forEach(b=>b.classList.remove('active'));
            const map = {dayGridMonth:'btn-mes',timeGridWeek:'btn-semana',timeGridDay:'btn-dia'};
            document.getElementById(map[v]).classList.add('active');
        }

        function cerrarModal(){
            document.getElementById('modal').classList.remove('open');
            citaActualId = null;
        }

        function cambiarEstado(estado){
            if(!citaActualId) return;
            fetch(`/citas/${citaActualId}/estado`, {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body:JSON.stringify({estado})
            }).then(r=>r.json()).then(data=>{
                if(data.ok){ cerrarModal(); cal.refetchEvents(); }
            });
        }

        document.getElementById('modal').addEventListener('click',function(e){
            if(e.target===this) cerrarModal();
        });
    </script>
</x-app-layout>
