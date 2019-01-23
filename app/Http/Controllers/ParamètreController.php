<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParamètreController extends Controller
{
    public function index($company_name){
        $company = \App\Company::where('name', $company_name)->first();
        return view('paramètre.index', compact('company'));
    }
}
