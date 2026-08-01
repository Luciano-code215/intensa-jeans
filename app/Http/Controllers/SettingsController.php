<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function social()
    {
        return view('admin.settings-social');
    }

    public function updateSocial(Request $request)
    {
        $request->validate([
            'whatsapp' => 'required|string|max:30',
            'instagram' => 'required|url|max:255',
            'facebook' => 'required|url|max:255',
        ]);

        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return back()->with('error', 'No se encontró el archivo .env.');
        }

        $env = file_get_contents($envPath);

        $reemplazos = [
            'WHATSAPP_OWNER' => $request->whatsapp,
            'INSTAGRAM_URL' => $request->instagram,
            'FACEBOOK_URL' => $request->facebook,
        ];

        foreach ($reemplazos as $key => $value) {
            $escaped = preg_match('/[#\s=]/', $value) ? '"' . $value . '"' : $value;
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $env)) {
                $env = preg_replace($pattern, "{$key}={$escaped}", $env);
            } else {
                $env .= "\n{$key}={$escaped}";
            }
        }

        file_put_contents($envPath, $env);

        \Illuminate\Support\Facades\Artisan::call('config:clear');

        return back()->with('success', 'Redes sociales actualizadas correctamente.');
    }
}
