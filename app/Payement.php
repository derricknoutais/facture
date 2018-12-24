<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payement extends Model
{
    protected $guarded = [];

    public function facture()
    {
        return $this->belongsTo('App\Facture');
    }
}
