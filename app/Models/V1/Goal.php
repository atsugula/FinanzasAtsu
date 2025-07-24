<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Goal
 *
 * @property $id
 * @property $name
 * @property $amount
 * @property $description
 * @property $date
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Goal extends Model
{
  use SoftDeletes;

  static $rules = [
    'name' => 'required',
    'amount' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'amount',
    'description',
    'created_by',
    'date',
  ];

  protected $appends = ['total_with_transactions'];

  public function getTotalWithTransactionsAttribute()
  {
      $transactionsTotal = $this->transactions()
          ->whereIn('status_id', [config('status.APR')]))
          ->sum('amount');

      return $this->amount - $transactionsTotal;
  }

  public function transactions()
  {
    return $this->hasMany(Transaction::class, 'goal_id', 'id');
  }

  public function savings()
  {
    return $this->hasMany(Saving::class, 'goal_id', 'id');
  }
}
