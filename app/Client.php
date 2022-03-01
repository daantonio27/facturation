<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $casts = [
        'tva' => 'boolean',
    ];

    protected $fillable = [
        'name', 'email', 'phone', 'document_type', 'document_id','tva'
    ];

    public function sales()
    {
        return $this->hasMany('App\Sale');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }
}
