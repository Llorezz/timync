<x-app-layout>
    <x-slot name="header">Estadísticas</x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Selector de año -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div style="font-size:13px; color:#64748b;">Mostrando datos del año {{ $año }}</div>
        <form method="GET" style="display:flex; gap:8px; align-items:center;">
            <select name="año" onchange="this.form.submit()" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:14px;">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $año == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">

        <!-- Citas por estado -->
        <div class="card" style="padding:24px;">
            <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:20px;">🥧 Citas por estado</h3>
            <div style="max-width:300px; margin:0 auto;">
                <canvas id="citasEstado"></canvas>
            </div>
        </div>

        <!-- Servicios más solicitados -->
        <div class="card" style="padding:24px;">
            <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:20px;">⭐ Servicios más solicitados</h3>
            <canvas id="serviciosTop"></canvas>
        </div>

        <!-- Clientes nuevos por mes -->
        <div class="card" style="padding:24px;">
            <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:20px;">👥 Clientes nuevos por mes</h3>
            <canvas id="clientesMes"></canvas>
        </div>

        <!-- Citas canceladas por mes -->
        <div class="card" style="padding:24px;">
            <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:20px;">❌ Citas canceladas por mes</h3>
            <canvas id="canceladasMes"></canvas>
        </div>

        <!-- Citas por empleado -->
        @if($citasPorEmpleado->count() > 0)
        <div class="card" style="padding:24px; grid-column:span 2;">
            <h3 style="font-size:15px; font-weight:600; color:#0f172a; margin-bottom:20px;">👤 Citas por empleado</h3>
            <canvas id="citasEmpleado" style="max-height:250px;"></canvas>
        </div>
        @endif

    </div>

    <script>
        const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        const colores = ['#0f4c81','#00b4d8','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'];

        function dataMeses(data) {
            return meses.map((_, i) => data[i + 1] || 0);
        }

        // Citas por estado
        new Chart(document.getElementById('citasEstado'), {
            type: 'doughnut',
            data: {
                labels: ['Pendiente', 'Confirmada', 'Cancelada'],
                datasets: [{
                    data: [
                        {{ $citasPorEstado['pendiente'] ?? 0 }},
                        {{ $citasPorEstado['confirmada'] ?? 0 }},
                        {{ $citasPorEstado['cancelada'] ?? 0 }}
                    ],
                    backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                    borderWidth: 0,
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });

        // Servicios top
        new Chart(document.getElementById('serviciosTop'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($serviciosTop->pluck('nombre')) !!},
                datasets: [{
                    label: 'Citas',
                    data: {!! json_encode($serviciosTop->pluck('total')) !!},
                    backgroundColor: colores,
                    borderRadius: 6,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // Clientes nuevos por mes
        new Chart(document.getElementById('clientesMes'), {
            type: 'line',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Clientes nuevos',
                    data: dataMeses({!! json_encode($clientesPorMes) !!}),
                    borderColor: '#0f4c81',
                    backgroundColor: 'rgba(15,76,129,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // Canceladas por mes
        new Chart(document.getElementById('canceladasMes'), {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Canceladas',
                    data: dataMeses({!! json_encode($canceladasPorMes) !!}),
                    backgroundColor: 'rgba(239,68,68,0.7)',
                    borderRadius: 6,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // Citas por empleado
        @if($citasPorEmpleado->count() > 0)
        new Chart(document.getElementById('citasEmpleado'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($citasPorEmpleado->pluck('nombre')) !!},
                datasets: [{
                    label: 'Citas',
                    data: {!! json_encode($citasPorEmpleado->pluck('total')) !!},
                    backgroundColor: colores,
                    borderRadius: 6,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
        @endif
    </script>

</x-app-layout>
