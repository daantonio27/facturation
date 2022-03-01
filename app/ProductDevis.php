<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDevis extends Model
{
    protected $fillable = [
        'devis_id', 'product_id', 'price', 'qty', 'total_amount'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    public function devis()
    {
        return $this->belongsTo('App\Devis');
    }
}
