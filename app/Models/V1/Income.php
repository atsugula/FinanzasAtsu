<?php

namespace App\Models\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Income
 *
 * @property $id
 * @property $user_id
 * @property $amount
 * @property $source
 * @property $date
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Income extends Model
{
  use SoftDeletes;

  protected $table = "incomes";

  static $rules = [
    'user_id' => 'required',
    'amount' => 'required',
    'source' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'amount',
    'source',
    'date'
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasOne
   */
  public function user()
  {
    return $this->hasOne(User::class, 'id', 'user_id');
  }

}