<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];
    public function devis()
    {
        return $this->hasMany('App\Devis', 'client_id');
    }
    public function factures()
    {
        return $this->hasMany('App\Facture', 'client_id');
    }
    public function companies()
    {
        return $this->belongsToMany('App\Company', 'client_companies', 'client_id', 'company_id');
    }
    public function solde(){
        $solde = 0;
        $factures = $this->factures;

        foreach ($factures as $facture) {
            $solde += $facture->resteAPayé();
        }
        
        return $solde;
    }

}
