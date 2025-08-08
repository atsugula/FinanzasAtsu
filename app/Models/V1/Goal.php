<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'name',
        'created_by',
        'target_amount',
        'current_amount',
        'target_date',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
