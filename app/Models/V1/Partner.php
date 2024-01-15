<?php

namespace App\Models\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Partner
 *
 * @property $id
 * @property $company_name
 * @property $type_document
 * @property $document_number
 * @property $phone
 * @property $email
 * @property $created_by
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property PaymentsHistory[] $paymentsHistories
 * @property TypeDocument $typeDocument
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Partner extends Model
{
    use SoftDeletes;

    static $rules = [
		'company_name' => 'required',
		'type_document' => 'required',
		'document_number' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_name','type_document','document_number','phone','email','created_by'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentsHistories()
    {
        return $this->hasMany('App\Models\V1\PaymentsHistory', 'partner_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function typeDocument()
    {
        return $this->hasOne(TypeDocument::class, 'id', 'type_document');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    

}
