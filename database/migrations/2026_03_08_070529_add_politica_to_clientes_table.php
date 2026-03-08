<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->boolean('politica_aceptada')->default(false)->after('provider');
            $table->timestamp('politica_aceptada_at')->nullable()->after('politica_aceptada');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['politica_aceptada', 'politica_aceptada_at']);
        });
    }
};
