<?php
// Auth::loginUsingId(1);
Route::get('/', 'CompanyController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Companies
Route::prefix('{company_name}')->group(function($company_name){
    // Devis
    Route::prefix('Devis')->group(function(){
        //Index
        Route::get('', 'DevisController@index');
        //Show
        Route::get('{numero}', 'DevisController@show');

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
    });
    //Client
    Route::prefix('Client')->group(function(){
        Route::get('', 'ClientController@index');
        Route::get('{client}', 'ClientController@show');
    }); 
    // Payements
    Route::prefix('Payement')->group(function(){
        Route::post('{facture}/addPayment', 'PayementController@store');
    });
});