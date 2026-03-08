<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ProcesarRecordatoriosCitas;
use App\Jobs\ProcesarNoShow;
use App\Jobs\ProcesarHuecosLibres;
use App\Jobs\ProcesarClientesInactivos;

// Recordatorios — cada hora para no perderse ninguna ventana
Schedule::job(new ProcesarRecordatoriosCitas)->hourly();

// No-show — cada hora para detectar citas pasadas
Schedule::job(new ProcesarNoShow)->hourly();

// Huecos libres y clientes inactivos — cada noche a las 20:00
Schedule::job(new ProcesarHuecosLibres)->dailyAt('20:00');
Schedule::job(new ProcesarClientesInactivos)->dailyAt('20:00');
