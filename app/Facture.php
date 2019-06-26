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

    public function resteAPayé(){
        return $this->montantTotal() - $this->totalPayé();
    }

    public static function numero($company_id){
        $numero = Facture::where('company_id', $company_id)->count() + 1;
        if($numero < 10){
            $numero_facture = 'F00' . $numero . '/' . date('Y');
        }
        else if( $numero < 100){
            $numero_facture = 'F0' . $numero . '/' . date('Y');
        } 
        else {
            $numero_facture = 'F0' . $numero . '/' . date('Y');
        }
        return $numero_facture;
    }

}
