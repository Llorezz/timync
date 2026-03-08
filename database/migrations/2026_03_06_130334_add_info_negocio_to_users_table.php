<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('direccion')->nullable()->after('descripcion_negocio');
            $table->string('ciudad')->nullable()->after('direccion');
            $table->string('horario_apertura')->nullable()->after('ciudad');
            $table->string('horario_cierre')->nullable()->after('horario_apertura');
            $table->string('instagram')->nullable()->after('horario_cierre');
            $table->string('facebook')->nullable()->after('instagram');
            $table->string('whatsapp_negocio')->nullable()->after('facebook');
            $table->json('fotos_galeria')->nullable()->after('whatsapp_negocio');
            $table->string('color_primario')->default('#0f4c81')->after('fotos_galeria');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'direccion', 'ciudad', 'horario_apertura', 'horario_cierre',
                'instagram', 'facebook', 'whatsapp_negocio', 'fotos_galeria', 'color_primario'
            ]);
        });
    }
};
