<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nombre');
            $table->string('tipo')->nullable(); // recordatorio, no_show, hueco_libre, cliente_inactivo, personalizado
            $table->string('asunto');
            $table->string('color_primario')->default('#0f4c81');
            $table->string('color_boton')->default('#00b4d8');
            $table->string('texto_boton')->nullable();
            $table->string('url_boton')->nullable();
            $table->text('cuerpo'); // HTML con variables {nombre}, {fecha}, etc.
            $table->boolean('predefinida')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
