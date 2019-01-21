<?php

namespace App\Http\Controllers;

use App\Payement;
use App\Facture;
use App\Company;
use Illuminate\Http\Request;

class PayementController extends Controller
{
    public function index($company_name)
    {
        $title = 'Cashier | Caisse';
        $company = Company::where('name', $company_name)->with(['factures', 'factures.payements', 'factures.payements.facture', 'retraits'])->first();
        $company->caisse();
        return view('caisse.index', compact('company', 'title'));
    }
    
    public function store(Request $request)
    {
        $facture = Facture::find($request->facture_id);
        
        if( ($facture->totalPayé() + $request->montant) < $facture->montantTotal() ){
            $facture->update([
                'etat' => 'Paiement Partiel'
            ]);
        } elseif(($facture->totalPayé() + $request->montant) == $facture->montantTotal()){
            $facture->update([
                'etat' => 'Payé'
            ]);
        } else {
            return "Erreur";
        }
        Payement::create([
            'facture_id' => $request->facture_id,
            'montant' => $request->montant,
            'note' => $request->note
        ]);
    }
}
