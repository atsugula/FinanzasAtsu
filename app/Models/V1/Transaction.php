<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'created_by',
        'category_id',
        'goal_id',
        'date',
        'note',
        'is_recurring',
        'recurring_interval_days',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'transaction_id', 'id');
    }

}
