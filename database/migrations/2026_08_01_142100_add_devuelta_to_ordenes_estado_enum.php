<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE ordenes MODIFY COLUMN estado ENUM('creada', 'pagada', 'entregada', 'cancelada', 'devuelta') NOT NULL DEFAULT 'creada'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE ordenes MODIFY COLUMN estado ENUM('creada', 'pagada', 'entregada', 'cancelada') NOT NULL DEFAULT 'creada'");
    }
};
