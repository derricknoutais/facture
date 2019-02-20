<?php

use App\Facture;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  X-CSRF-TOKEN, X-Requested-With, Content-Type, X-Auth-Token, Origin, Authorization');

Auth::loginUsingId(1);
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::post('/api/facture', function(Request $request){
    $facture = Facture::create([
        'company_id' => 1,
        'etabli_par' => 1,
        'etat' => 'Validé',
        'objet' => $request->objet,
        'date' => today(),
        'échéance' => $request->échéance,
        'taxable' => false
    ]);
    App\FactureEntree::create([
        'facture_id' => $facture->id,
        'quantité' => $request->quantité,
        'description' => $request->description,
        'prix_unitaire' => $request->prix_unitaire
    ]);

    return $request;
});




Route::group(['middleware' => ['auth']], function () {
    
    Route::get('/accueil', 'CompanyController@index');
    Route::get('/', 'CompanyController@index');
    // Companies
    Route::prefix('{company_name}')->group(function($company_name){
        Route::post('fermer-caisse', 'CompanyController@fermerCaisse');
        // Devis
        Route::prefix('Devis')->group(function(){
            //Index
            Route::get('', 'DevisController@index');

            //Show
            Route::get('{numero}', 'DevisController@show');

            //Store
            Route::post('/store', 'DevisController@store');

            //Delete Entries
            Route::post('{numero}/deleteSelectedItems', 'DevisController@destroyEntries');

            //Delete Devis
            Route::post('/deleteSelectedDocuments', 'DevisController@destroyDevis');

            //Rejette Devis
            Route::post('/rejetter', 'DevisController@modifierEtat');

            //Update
            Route::post('{numero}/updateSelectedItems', 'DevisController@updateEntries');

            //Sauvegarde Line
            Route::post('{numero}/saveLine', 'DevisController@saveLine');

            //Sauvegarde Infos
            Route::post('{numero}/saveInfo', 'DevisController@saveInfo');
        });
        // Facture
        Route::prefix('Facture')->group(function(){
            //Index
            Route::get('', 'FactureController@index');
            //Show
            Route::get('{numero}', 'FactureController@show');
            Route::post('/store', 'FactureController@store');
            //Delete Entries
            Route::post('{numero}/deleteSelectedItems', 'FactureController@destroyEntries');

            //Delete Devis
            Route::post('/deleteSelectedDocuments', 'FactureController@destroyDevis');

            //Rejette Devis
            Route::post('/rejetter', 'FactureController@modifierEtat');

            //Update
            Route::post('{numero}/updateSelectedItems', 'FactureController@updateEntries');

            //Sauvegarde Line
            Route::post('{numero}/saveLine', 'FactureController@saveLine');

            //Sauvegarde Infos
            Route::post('{numero}/saveInfo', 'FactureController@saveInfo');

            Route::get('{facture}/api', 'FactureController@getFacture');
        });
        //Client
        Route::prefix('Client')->group(function(){
            Route::get('', 'ClientController@index');
            Route::get('{client}', 'ClientController@show');
            Route::post('store', 'ClientController@store');
        }); 
        // Payements
        Route::prefix('Payement')->group(function(){
            Route::get('/', 'PayementController@index');
            Route::post('{facture}/addPayment', 'PayementController@store');
            Route::post('retirer-cash', 'RetraitController@store');
        });
        // Paramètres
        Route::get('paramètres', 'ParamètreController@index');
    });
});


