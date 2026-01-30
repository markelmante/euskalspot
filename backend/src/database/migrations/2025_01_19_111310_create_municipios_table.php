<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('provincia'); 
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);
            $table->timestamps();

            // Evitar duplicados (mismo nombre en misma provincia)
            $table->unique(['nombre', 'provincia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};