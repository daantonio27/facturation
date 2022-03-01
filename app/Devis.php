<?php

namespace App;

use App\User;
use App\Client;
use App\ProductDevis;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    protected $casts = [
        'tva' => 'boolean',
    ];

    protected $fillable = [
        'client_id', 'user_id','validate','reference','tva'
    ];
    public function client() {
        return $this->belongsTo(Client::class,'client_id');
    }
    public function products() {
        return $this->hasMany(ProductDevis::class,'devis_id');
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
