<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_companies', 'company_id', 'user_id');
    }
    public function clients()
    {
        return $this->belongsToMany('App\Client', 'client_companies', 'company_id', 'client_id');
    }
    public function devis()
    {
        return $this->hasMany('App\Devis', 'company_id');
    }
    public function factures()
    {
        return $this->hasMany('App\Facture', 'company_id');
    }

}
