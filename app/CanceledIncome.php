<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CanceledIncome extends Model
{
    protected $fillable = [
        'title', 'reference', 'amount', 'payment_method_id', 'client_id', 'user_id', 'sale_id'
    ];

    public function method()
    {
        return $this->belongsTo('App\PaymentMethod', 'payment_method_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }



    public function sale()
    {
        return $this->belongsTo('App\Sale');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }


    public function scopeFindByPaymentMethodId($query, $id)
    {
        return $query->whereMonth('created_at', Carbon::now()->month);
    }

}
