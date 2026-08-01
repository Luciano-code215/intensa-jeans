<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Valores iniciales desde el .env (compatibilidad con la instalación local)
        DB::table('settings')->insertOrIgnore([
            ['key' => 'whatsapp_owner', 'value' => env('WHATSAPP_OWNER', '543795016705')],
            ['key' => 'instagram_url', 'value' => env('INSTAGRAM_URL', 'https://instagram.com/intensa.ok')],
            ['key' => 'facebook_url', 'value' => env('FACEBOOK_URL', 'https://facebook.com/intensa.ok')],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
