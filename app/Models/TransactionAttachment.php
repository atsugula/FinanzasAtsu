<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionAttachment extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'path',
        'is_temp',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
