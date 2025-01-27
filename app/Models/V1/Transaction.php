<?php

namespace App\Models\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transaction
 *
 * @property $id
 * @property $created_by
 * @property $amount
 * @property $date
 * @property $description
 * @property $source
 * @property $category
 * @property $goal_id
 * @property $goal
 * @property $status
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property ExpensesCategory $expensesCategory
 * @property Goal $goal
 * @property Status $status
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Transaction extends Model
{
    use SoftDeletes;

    static $rules = [
        'amount' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by',
        'type',
        'amount',
        'date',
        'description',
        'source',
        'category',
        'goal_id',
        'goal',
        'status_id'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function expensesCategory()
    {
        return $this->hasOne('App\Models\V1\ExpensesCategory', 'id', 'category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function goalRelation()
    {
        return $this->hasOne('App\Models\V1\Goal', 'id', 'goal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
