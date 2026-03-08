<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['admin', 'negocio'])->default('negocio')->after('email');
            $table->string('nombre_negocio')->nullable()->after('rol');
            $table->string('telefono_negocio')->nullable()->after('nombre_negocio');
            $table->boolean('activo')->default(true)->after('telefono_negocio');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rol', 'nombre_negocio', 'telefono_negocio', 'activo']);
        });
    }
};
