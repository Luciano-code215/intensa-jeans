<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function edit()
    {
        return view('admin.banner');
    }

    public function update(Request $request)
    {
        $request->validate([
            'banner_desktop' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'banner_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        if ($request->hasFile('banner_desktop')) {
            $request->file('banner_desktop')->move(public_path('images'), 'banner1.jpeg');
        }

        if ($request->hasFile('banner_mobile')) {
            $request->file('banner_mobile')->move(public_path('images'), 'banner1-mobile.jpg');
        }

        return redirect()->route('admin.banner')->with('success', 'Banner actualizado correctamente.');
    }
}
