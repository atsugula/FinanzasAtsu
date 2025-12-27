<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'currency' => $this->currency,
            'month_start_day' => (int) $this->month_start_day,
        ];
    }
}
