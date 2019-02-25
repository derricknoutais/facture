<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];
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
    public function caisse(){
        $this->loadMissing(['factures', 'factures.payements']);
        $totalPaiements = 0;
        foreach($this->factures as $facture){
            foreach($facture->payements as $payement){
                $totalPaiements += $payement->montant;
            }
        }
        return $totalPaiements;
    }
    public function retraits(){
        return $this->hasMany('App\Retrait', 'company_id');
    }
    public function paramètres()
    {
        return $this->hasOne('App\Paramètre', 'company_id');
    }
}
