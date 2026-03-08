<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('nombre_negocio');
            $table->string('foto_portada')->nullable()->after('slug');
            $table->text('descripcion_negocio')->nullable()->after('foto_portada');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['slug', 'foto_portada', 'descripcion_negocio']);
        });
    }
};
