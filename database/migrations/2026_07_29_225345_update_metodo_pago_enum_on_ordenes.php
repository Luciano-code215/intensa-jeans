<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE ordenes MODIFY COLUMN metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia', 'tarjeta_debito', 'tarjeta_credito') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ordenes MODIFY COLUMN metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia') NULL");
    }
};
