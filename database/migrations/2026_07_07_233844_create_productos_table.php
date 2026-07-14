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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->string('url_imagen')->nullable();
            $table->integer('porc_desc_ef')->default(0); // 0 por defecto
            $table->decimal('precio_ef', 10, 2)->default(0.00);
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->string('sku')->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('liquidacion')->default(false);
            $table->integer('porc_liquidacion')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
