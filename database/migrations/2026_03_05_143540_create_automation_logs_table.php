<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('automation_rule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cita_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('canal');
            $table->string('destinatario')->nullable();
            $table->text('mensaje');
            $table->enum('estado', ['enviado', 'fallido', 'pendiente'])->default('pendiente');
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_logs');
    }
};
