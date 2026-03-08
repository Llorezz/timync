<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('descripcion');
            $table->text('descripcion_larga')->nullable()->after('foto');
            $table->unsignedBigInteger('empleado_id')->nullable()->after('descripcion_larga');
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn(['foto', 'descripcion_larga', 'empleado_id']);
        });
    }
};
