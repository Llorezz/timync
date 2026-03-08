<?php

namespace App\Console\Commands;

use App\Jobs\ProcesarRecordatoriosCitas;
use Illuminate\Console\Command;

class TestRecordatorio extends Command
{
    protected $signature   = 'test:recordatorio';
    protected $description = 'Prueba el job de recordatorios';

    public function handle(): void
    {
        $this->info('Ejecutando job de recordatorios...');
        (new ProcesarRecordatoriosCitas())->handle();
        $this->info('Job ejecutado. Revisa los logs en /automatizaciones');
    }
}
