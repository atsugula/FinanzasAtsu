<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Status
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Status extends Model
{
    
  static $rules = [
    'name' => 'required',
  ];

  protected $perPage = 20;

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['name','description'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function expenses()
  {
    return $this->hasMany(Status::class, 'status', 'id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function incomes()
  {
    return $this->hasMany(Status::class, 'status', 'id');
  }

}
