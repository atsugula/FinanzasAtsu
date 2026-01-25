<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'initial_balance',
        'is_archived',
    ];

    protected $casts = [
        'initial_balance' => 'decimal:2',
        'is_archived' => 'boolean',
    ];

    protected $appends = [
        'current_balance',
        'current_balance_formatted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getCurrentBalanceAttribute(): float
    {
        return (float) ($this->transactions()->sum('amount') ?? 0);
    }

    public function getCurrentBalanceFormattedAttribute(): string
    {
        return number_format($this->current_balance, 2, '.', ',');
    }

}
