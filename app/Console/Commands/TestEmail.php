<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestEmail extends Command
{
    protected $signature   = 'test:email';
    protected $description = 'Prueba el envío de email directamente';

    public function handle(): void
    {
        $settings = Setting::where('user_id', 1)->first();

        if (!$settings) {
            $this->error('No hay configuración de email.');
            return;
        }

        $this->info('Configurando SMTP...');
        $this->info('Host: ' . $settings->mail_host);
        $this->info('Port: ' . $settings->mail_port);
        $this->info('User: ' . $settings->mail_username);
        $this->info('From: ' . $settings->mail_from_address);

        config([
            'mail.default'                 => 'smtp',
            'mail.mailers.smtp.host'       => $settings->mail_host,
            'mail.mailers.smtp.port'       => $settings->mail_port ?? 465,
            'mail.mailers.smtp.username'   => $settings->mail_username,
            'mail.mailers.smtp.password'   => $settings->mail_password,
            'mail.mailers.smtp.encryption' => $settings->mail_port == 465 ? 'ssl' : 'tls',
            'mail.from.address'            => $settings->mail_from_address,
            'mail.from.name'               => 'Timync Test',
        ]);

        try {
            $this->info('Enviando email de prueba a ' . $settings->mail_from_address . '...');
            Mail::raw('Este es un email de prueba desde Timync.', function (Message $msg) use ($settings) {
                $msg->to($settings->mail_from_address)
                    ->subject('Test Timync - ' . now()->format('H:i:s'));
            });
            $this->info('✅ Email enviado correctamente.');
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
        }
    }
}
