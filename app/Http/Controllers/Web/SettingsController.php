<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = auth()->user()->settings; // 1:1 (tu observer lo crea)
        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'currency' => ['required', 'string', 'size:3'],
            'month_start_day' => ['required', 'integer', 'min:1', 'max:28'],
        ]);

        $settings = auth()->user()->settings;
        $settings->update($data);

        return redirect()->route('settings.edit')
            ->with('success', 'Ajustes actualizados.');
    }
}
