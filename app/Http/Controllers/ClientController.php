<?php

namespace App\Http\Controllers;

use App\Client;
use App\Company;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index($company_name)
    {
        $company = Company::where('name', $company_name)->first();
        $title = 'Cashier | Client';
        $clients = $company->clients;
        
        return view('client.index', compact(['clients', 'company', 'title']));
    }
    public function create()
    {
        //
    }
    public function store($company, Request $request)
    {
        $company = Company::where('name', $company)->first();
        $company->clients()->save(
            Client::create([
                'nom' => $request->nom,
                'prénom' => $request->prenom,
                'addresse' => $request->addresse,
                'numéro' => $request->numéro
            ])
        );
        
    }
    public function show($company, Client $client)
    {
        $company = Company::where('name', $company)->first();
        $title = 'Cashier | Détails ' . $client->nom . ' ' . $client->prénom;
        $client->loadMissing('factures', 'devis', 'factures.créateur', 'factures.entrees', 'factures.payements', 'devis.créateur', 'devis.entrees');
        return view('client.show', compact('client', 'company', 'title'));
    }
    public function edit(Client $client)
    {
        //
    }
    public function update(Request $request, Client $client)
    {
        //
    }
    public function destroy(Client $client)
    {
        //
    }
}
