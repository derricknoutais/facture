<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paramètre;

class ParamètreController extends Controller
{
    public function index($company_name){
        $company = \App\Company::where('name', $company_name)->first();
        $paramètres = $company->paramètres;
        return view('paramètre.index', compact('company', 'paramètres'));
    }
}
