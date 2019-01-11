<?php

namespace App\Http\Controllers;

use App\Payement;
use App\Facture;
use App\Company;
use Illuminate\Http\Request;

class PayementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_name)
    {
        $title = 'Cashier | Caisse';
        $company = Company::where('name', $company_name)->with(['factures', 'factures.payements', 'factures.payements.facture', 'retraits'])->first();

        $company->caisse();
        return view('caisse.index', compact('company', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $facture = Facture::find($request->facture_id);
        
        if(($facture->totalPayé() + $request->montant) < $facture->montantTotal()){
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
            'montant' => $request->montant
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payement  $payement
     * @return \Illuminate\Http\Response
     */
    public function show(Payement $payement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payement  $payement
     * @return \Illuminate\Http\Response
     */
    public function edit(Payement $payement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payement  $payement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payement $payement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payement  $payement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payement $payement)
    {
        //
    }
}
