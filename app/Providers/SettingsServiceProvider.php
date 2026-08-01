<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Carga los ajustes de redes sociales desde la base de datos
     * y los expone como config('app.*') para toda la app.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                foreach (Setting::all() as $setting) {
                    config(["app.{$setting->key}" => $setting->value]);
                }
            }
        } catch (\Throwable $e) {
            // Si la base de datos no está disponible aún, se usan los valores del .env
        }
    }
}
