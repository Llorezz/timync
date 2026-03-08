<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Email
            $table->string('mail_host')->nullable();
            $table->integer('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();

            // Telegram
            $table->string('telegram_bot_token')->nullable();
            $table->string('telegram_chat_id')->nullable();

            // WhatsApp
            $table->string('whatsapp_token')->nullable();
            $table->string('whatsapp_phone_id')->nullable();

            // Negocio
            $table->string('negocio_nombre')->nullable();
            $table->string('negocio_telefono')->nullable();
            $table->string('negocio_email')->nullable();
            $table->string('negocio_direccion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
