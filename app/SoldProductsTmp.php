<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoldProductsTmp extends Model
{
    protected $fillable = [
        'sale_id', 'product_id', 'price', 'qty', 'total_amount','user_id'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    public function sale()
    {
        return $this->belongsTo('App\Sale');
    }
}
