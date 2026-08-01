<?php

namespace App\Http\Controllers;

use App\Models\Setting;
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
            'whatsapp' => 'required|string|max:30|regex:/^[^\r\n]+$/',
            'instagram' => 'required|url|max:255|regex:/^[^\r\n]+$/',
            'facebook' => 'required|url|max:255|regex:/^[^\r\n]+$/',
        ]);

        Setting::set('whatsapp_owner', $request->whatsapp);
        Setting::set('instagram_url', $request->instagram);
        Setting::set('facebook_url', $request->facebook);

        // Reflejamos los nuevos valores en config para la petición actual
        config([
            'app.whatsapp_owner' => $request->whatsapp,
            'app.instagram_url' => $request->instagram,
            'app.facebook_url' => $request->facebook,
        ]);

        return back()->with('success', 'Redes sociales actualizadas correctamente.');
    }
}
