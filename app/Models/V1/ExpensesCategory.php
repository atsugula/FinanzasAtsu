<?php

namespace App\Models\V1;

use App\Models\V1\Expense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ExpensesCategory
 *
 * @property $id
 * @property $name
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Expense[] $expenses
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ExpensesCategory extends Model
{
    use SoftDeletes;

    static $rules = [
      'name' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category', 'id');
    }
    

}
