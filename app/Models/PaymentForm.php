<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentForm extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','payment_form','description'];

    /**
     * Get all of the PaymentSupplier for the PaymentForm
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function PaymentSupplier()
    {
        return $this->hasMany(PaymentSupplier::class, 'payment_form_id', 'id');
    }

}
