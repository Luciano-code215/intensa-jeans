<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('imagen_productos', function (Blueprint $table) {
            $table->id();
            // Conectamos cada imagen con su respectivo producto
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->string('url_imagen'); // Ruta de la foto adicional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagen_productos');
    }
};
