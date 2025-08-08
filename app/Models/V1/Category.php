<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'type',
        'created_by'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
