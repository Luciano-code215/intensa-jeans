<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->string('nombre_contacto')->nullable()->after('user_id');
            $table->string('telefono_contacto')->nullable()->after('nombre_contacto');
            $table->foreignId('user_id')->nullable()->change();
        });

        Schema::table('item_ordenes', function (Blueprint $table) {
            $table->string('talle')->nullable()->after('producto_id');
        });
    }

    public function down(): void
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->dropColumn('nombre_contacto');
            $table->dropColumn('telefono_contacto');
            $table->foreignId('user_id')->nullable(false)->change();
        });

        Schema::table('item_ordenes', function (Blueprint $table) {
            $table->dropColumn('talle');
        });
    }
};
