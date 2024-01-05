<?php

namespace App\Models\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Expense
 *
 * @property $id
 * @property $user_id
 * @property $category
 * @property $amount
 * @property $description
 * @property $date
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property ExpensesCategory $expensesCategory
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Expense extends Model
{
    use SoftDeletes;

    static $rules = [
		'user_id' => 'required',
		'category' => 'required',
		'amount' => 'required',
		'description' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','category','amount','description','date'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function expensesCategory()
    {
        return $this->hasOne(ExpensesCategory::class, 'id', 'category');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    

}
