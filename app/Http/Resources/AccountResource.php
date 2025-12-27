<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'initial_balance' => (float) $this->initial_balance,
            'is_archived' => (bool) $this->is_archived,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
