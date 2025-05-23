<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('codigoempresa', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->text('barcode')->nullable();
            $table->foreignId('empresa_id')->constrained('empresa')->onDelete('cascade'); // Relación con tarifas

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigoempresa');
    }
};
