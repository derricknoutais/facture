<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
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
        return $this->hasMany('App\FactureEntree', 'facture_id');
    }
    public function client()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }
    public function payements()
    {
        return $this->hasMany('App\Payement', 'facture_id');
    }

    public function totalPayé(){
        $total = 0;
        foreach($this->payements as $payement){
            $total += $payement->montant;
        }
        return $total;
    }
    public function montantTotal(){
        $total = 0;
        foreach ($this->entrees as $entree) {
            $total += $entree->quantité * $entree->prix_unitaire;
        }
        return $total;
    }

}
