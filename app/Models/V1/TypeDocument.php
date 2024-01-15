<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TypeDocument
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @property Partner[] $partners
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TypeDocument extends Model
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
    public function partners()
    {
        return $this->hasMany('App\Models\V1\Partner', 'type_document', 'id');
    }
    

}
