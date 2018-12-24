<?php

namespace App\Http\Controllers;

use App\Devis;
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
        $devis = Company::where('name', $company_name)->first()->devis;
        $clients = Client::all();
        $vendeurs = Company::where('name', $company_name)->first()->users;
        $devis->loadMissing('créateur', 'company', 'client');
        return view('devis.index', compact('devis', 'clients', 'vendeurs'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Devis  $devis
     * @return \Illuminate\Http\Response
     */
    public function show($company_name, $numero)
    {
        $company = Company::where('name', $company_name)->first();
        $devis = Devis::where(['company_id' => $company->id, 'numéro' => $numero])->first();
        $devis->loadMissing(['entrees', 'company', 'client', 'créateur']);
        $clients = Client::all();
        return view('devis.show', compact('devis', 'clients'));
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
        $devis = Devis::where('numéro', $numero)->first();
        $devis->loadMissing('entrees');
        $ligne = new DevisEntree();
        $ligne->quantité = $request['quantité'];
        $ligne->description = $request['description'];
        $ligne->prix_unitaire = $request['prix_unitaire'];

        $devis->entrees()->save($ligne);
    }

    public function saveInfo($company_name, $numero, Request $request){
        $devis = Devis::where('numéro', $numero)->first();
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
        $devis = Devis::where('numéro', $numero)->first();
        $devis->loadMissing('entrees');
        for ($i=0; $i < sizeof($request->all()) ; $i++) { 
            if($request[$i] == true){
                $devis->entrees[$i]->delete();
            }
        }
    }


}
