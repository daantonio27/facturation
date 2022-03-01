<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference','name', 'description', 'product_category_id','product_unite_id', 'price', 'stock', 'stock_defective'
    ];

    public function category()
    {
        return $this->belongsTo('App\ProductCategory', 'product_category_id')->withTrashed();
    }

    public function unite()
    {
        return $this->belongsTo('App\Unite', 'product_unite_id');
    }

    public function solds()
    {
        return $this->hasMany('App\SoldProduct');
    }

    public function receiveds()
    {
        return $this->hasMany('App\ReceivedProduct');
    }
}
