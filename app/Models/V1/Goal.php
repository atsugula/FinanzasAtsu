<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'type',
        'name',
        'created_by',
        'target_amount',
        'current_amount',
        'target_date',
    ];

    public static $rules = [
        'type'           => 'required|in:saving,debt_in,debt_on',
        'name'           => 'required|string|max:255',
        'target_amount'  => 'required|numeric|min:0',
        'current_amount' => 'nullable|numeric|min:0',
        'target_date'    => 'nullable|date|after_or_equal:today',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
