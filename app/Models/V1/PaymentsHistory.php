<?php

namespace App\Models\V1;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaymentsHistory
 *
 * @property $id
 * @property $paid
 * @property $payable
 * @property $date
 * @property $description
 * @property $status
 * @property $partner_id
 * @property $created_by
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @property Partner $partner
 * @property User $user
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PaymentsHistory extends Model
{
    use SoftDeletes;

    protected $table = "payments_history";

    static $rules = [
		'paid' => 'required',
		'status' => 'required',
		'partner_id' => 'required',
        'date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'paid',
        'payable',
        'date',
        'description',
        'status',
        'partner_id',
        'created_by',
        'transaction_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function partner()
    {
        return $this->hasOne(Partner::class, 'id', 'partner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function expense()
    {
        return $this->hasOne(Expense::class, 'id', 'transaction_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function statuses()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }    

}
