<?php

namespace App\Models\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'created_by',
        'category_id',
        'goal_id',
        'status_id',
        'date',
        'note',
        'is_recurring',
        'recurring_interval_days',
    ];

    protected $with = [
        'creator'
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

}
