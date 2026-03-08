<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tipo'); // recordatorio, no_show, hueco_libre, cliente_inactivo
            $table->string('nombre');
            $table->json('config'); // configuración flexible en JSON
            $table->enum('canal', ['email', 'telegram', 'whatsapp']);
            $table->boolean('activo')->default(true);
            $table->timestamp('ultima_ejecucion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_rules');
    }
};
