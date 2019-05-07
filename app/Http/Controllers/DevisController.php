<?php

namespace App\Http\Controllers;

use App\Devis;
use App\Facture;
use App\DevisEntree;
use App\Company;
use App\Client;
use App\User;
use Auth;
use Illuminate\Http\Request;

class DevisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company_name)
    {
        $title = "Cashier | Devis ";
        $company =  Company::where('name', $company_name)->first();
        $devis = Company::where('name', $company_name)->first()->devis;
        $clients =  Company::where('name', $company_name)->first()->clients;
        $vendeurs = Company::where('name', $company_name)->first()->users;
        $user = Auth::user();


        $devis->loadMissing('créateur', 'company', 'client');
        return view('devis.index', compact('devis', 'clients', 'vendeurs', 'title', 'company' , 'user'));
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
    public function store(Request $request)
    {
        $devis = Devis::create([
            'numéro' => $request['document']['numero'],
            'company_id' => $request->company,
            'etabli_par' => Auth::user()->id,
            'client_id'=> $request['document']['client'],
            'etat' => 'Ouvert',
            'taxable' => $request['document']['taxable'],
        ]);
        if($devis)
            return $devis;
    }
    public function show($company_name, $numero)
    {
    
        $company = Company::where('name', $company_name)->first();
        $company->loadMissing('services', 'paramètres');
        $devis = Devis::find($numero);
        $title = "Cashier | Devis Nº " . $devis->numéro ;
        $factures = Facture::where('company_id', $company->id)->get();
        $devis->loadMissing(['entrees', 'company', 'client', 'créateur']);
        $clients = $company->clients;
        $user = Auth::user();
        return view('devis.show', compact('devis', 'clients', 'factures', 'title', 'user', 'company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Devis  $devis
     * @return \Illuminate\Http\Response
     */
    public function edit(Devis $devis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Devis  $devis
     * @return \Illuminate\Http\Response
     */
    public function updateEntries($company, $numero, Request $request)
    {
        $devis = Devis::where('numéro', $numero)->first();
        $devis->loadMissing('entrees');
        for ($i=0; $i < sizeof($request->all()) ; $i++) { 
            $devis->entrees[$i]->update([
                'quantité' => $request[$i]['quantité'],
                'description' => $request[$i]['description'],
                'prix_unitaire' => $request[$i]['prix_unitaire'],
            ]);
        }
    }

    public function saveLine($company_name, $numero, Request $request){
        $devis = Devis::find($numero);
        $devis->loadMissing('entrees');
        $ligne = new DevisEntree();
        $ligne->quantité = $request['quantité'];
        $ligne->description = $request['description'];
        $ligne->prix_unitaire = $request['prix_unitaire'];

        $devis->entrees()->save($ligne);
    }

    public function saveInfo($company_name, $numero, Request $request){
        $devis = Devis::find( $numero);
        $devis->update([
            'client_id' => $request['client_id'],
            'date' => $request['date'],
            'objet' => $request['objet']
        ]);
    }
    public function modifierEtat($company_name, Request $request){
        $facture = Devis::find($request['document']['id']);
        $facture->update([
            'etat' => $request['etat'],
            'verifie_par' => Auth::user()->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Devis  $devis
     * @return \Illuminate\Http\Response
     */
    public function destroyDevis($company, Request $request)
    {
        $devis = Company::where('name', $company)->first()->devis;
        for ($i=0; $i < sizeof($request->all()) ; $i++) { 
            if($request[$i] == true){
                $devis[$i]->delete();
            }
        }
    }

    public function destroyEntries($company, $numero, Request $request)
    {
        $devis = Devis::find($numero);
        $devis->loadMissing('entrees');
        for ($i=0; $i < sizeof($request->all()) ; $i++) { 
            if($request[$i] == true){
                $devis->entrees[$i]->delete();
            }
        }
    }


}
