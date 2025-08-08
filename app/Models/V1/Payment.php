<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'amount',
        'status',
        'payment_date',
        'notes'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
