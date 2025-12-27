<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateSettingsRequest;
use App\Http\Resources\SettingsResource;

class SettingsController extends Controller
{
    public function show()
    {
        $settings = auth()->user()->settings;
        return new SettingsResource($settings);
    }

    public function update(UpdateSettingsRequest $request)
    {
        $settings = auth()->user()->settings;
        $settings->update($request->validated());

        return new SettingsResource($settings->fresh());
    }
}
