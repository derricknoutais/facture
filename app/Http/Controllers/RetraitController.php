<?php

namespace App\Http\Controllers;

use App\Retrait;
use Illuminate\Http\Request;

class RetraitController extends Controller
{
    public function store($company_name, Request $request){

        $company = \App\Company::where('name', $company_name)->first();
        $retrait = Retrait::create([
            'company_id' => $company->id,
            'montant' => $request->montant,
            'note' => $request->note
        ]);
    }
}
