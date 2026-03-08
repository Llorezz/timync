<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios_negocio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('dia_semana'); // 0=Lunes, 1=Martes... 6=Domingo
            $table->boolean('activo')->default(true);
            $table->time('hora_apertura')->nullable();
            $table->time('hora_cierre')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'dia_semana']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_negocio');
    }
};
