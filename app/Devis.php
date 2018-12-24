<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    protected $guarded = [];
    public function créateur()
    {
        return $this->hasOne('App\User', 'id', 'etabli_par');
    }
    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }
    public function entrees()
    {
        return $this->hasMany('App\DevisEntree', 'devis_id');
    }
    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }
}
