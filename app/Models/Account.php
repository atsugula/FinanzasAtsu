<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        $sum = $this->getAttribute('transactions_sum_amount');

        return (float) $this->initial_balance + (float) ($sum ?? 0);
    }

    public function getCurrentBalanceFormattedAttribute(): string
    {
        return number_format((float) $this->current_balance, 2, '.', ',');
    }
}
