<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleado_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('dia_semana'); // 0=domingo, 1=lunes... 6=sabado
            $table->boolean('activo')->default(true);
            $table->time('hora_inicio_manana')->nullable();
            $table->time('hora_fin_manana')->nullable();
            $table->time('hora_inicio_tarde')->nullable();
            $table->time('hora_fin_tarde')->nullable();
            $table->timestamps();

            $table->unique(['empleado_id', 'dia_semana']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleado_horarios');
    }
};
