<?php
namespace App\Http\Controllers;

use App\Facture;
use App\FactureEntree;
use App\Company;
use App\Client;
use App\Events\FactureCree;
use App\User;
use Auth;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    public function index($company_name)
    {
        $title = "Cashier | Facture ";
        $company = Company::where('name', $company_name)->first();
        $company->loadMissing('services');
        $facture = $company->factures;
        $clients = $company->clients;
        $vendeurs = Company::where('name', $company_name)->first()->users;
        $user = Auth::user();

        $facture->loadMissing('créateur', 'company', 'client');
        return view('facture.index', compact('facture', 'clients', 'vendeurs', 'company', 'title', 'user'));
    }

    public function store($company, Request $request)
    {
        $facture = Facture::create([
            'numéro' => $request['document']['numero'],
            'company_id' => $request->company,
            'etabli_par' => Auth::user()->id,
            'client_id'=> $request['document']['client'],
            'taxable' => $request['document']['taxable'],
            'etat' => 'Ouvert',
            // 'created_by'
        ]);
        
        // Si la facture est créée
        if($facture){
            // Si on veut changer Devis en Facture
            if(isset($request['document']['entrees'])){
                $facture->update([
                    'objet' => $request['infos']['objet'],
                    'date' => $request['infos']['date'],
                ]);
                // Boucle les entrees du devis et cree pour $facture
                foreach ($request['document']['entrees'] as $entree) {
                    FactureEntree::create([
                        'facture_id' => $facture->id,
                        'quantité' => $entree['quantité'],
                        'description' => $entree['description'],
                        'prix_unitaire' => $entree['prix_unitaire']
                    ]);
                }
            }
            // FactureCree::dispatch($facture);
            
            return $facture;
        }
    }
    
    public function show($company_name, $numero)
    {
        $company = Company::where('name', $company_name)->first();
        if($company->paramètres && $company->services){
            $company->loadMissing('paramètres', 'services');
        }
        
        $facture = Facture::find($numero);
        $title = "Cashier | Facture ";
        $facture->loadMissing(['entrees', 'company', 'client', 'créateur', 'payements']);
        $clients = $company->clients;
        $user = Auth::user();
        return view('facture.show', compact('facture', 'clients', 'title', 'user', 'company'));
    }

    public function updateEntries($company, $numero, Request $request)
    {
        $facture = Facture::find($numero);
        $facture->loadMissing('entrees');
        for ($i=0; $i < sizeof($request->all()) ; $i++) {
            $facture->entrees[$i]->update([
                'quantité' => $request[$i]['quantité'],
                'description' => $request[$i]['description'],
                'prix_unitaire' => $request[$i]['prix_unitaire'],
            ]);
        }
    }

    public function saveLine($company_name, $numero, Request $request)
    {
        $facture = Facture::find($numero);
        $facture->loadMissing('entrees');
        $ligne = new FactureEntree();
        $ligne->quantité = $request['quantité'];
        $ligne->description = $request['description'];
        $ligne->prix_unitaire = $request['prix_unitaire'];

        return $facture->entrees()->save($ligne);
    }
    public function saveInfo($company_name, $numero, Request $request)
    {
        $facture = Facture::find($numero);
        if($request['client_id'] != null){
            $facture->update([
                'client_id' => $request['client_id'],
            ]);
        }
        if($request['date'] != null){
            $facture->update([
                'date' => $request['date'],
            ]);
        }
        if($request['objet'] != null){
            $facture->update([
                'objet' => $request['objet'],
            ]);
        }
        if($request['échéance'] != null){
            $facture->update([
                'échéance' => $request['échéance']
            ]);
        }
    }

    public function modifierEtat($company_name, Request $request)
    {
        $facture = Facture::find($request['document']['id']);
        $facture->update([
            'etat' => $request['etat'],
            'verifie_par' => Auth::user()->id
        ]);
    }

    public function destroyDevis($company, Request $request)
    {
        $facture = Company::where('name', $company)->first()->factures;
        for ($i=0; $i < sizeof($request->all()) ; $i++) { 
            if($request[$i] == true){
                $facture[$i]->delete();
            }
        }
    }

    public function destroyEntries($company, $numero, Request $request)
    {
        $facture = Facture::find($numero);
        $facture->loadMissing('entrees');
        for ($i=0; $i < sizeof($request->all()) ; $i++) { 
            if($request[$i] == true){
                $facture->entrees[$i]->delete();
            }
        }
    }

    public function getFacture($company_name, Facture $facture){
        return $facture->loadMissing('client', 'payements', 'entrees', 'company');
    }

}
