<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Paramètre;
use App\Company;
use Illuminate\Foundation\Auth;

class ParamètreController extends Controller
{
    public function index($company_name){
        $company = \App\Company::where('name', $company_name)->first();
        $paramètres = $company->paramètres;
        return view('paramètre.index', compact('company', 'paramètres'));
    }
    public function store(Request $request){

        if($request->hasFile('logo')){
            $image = $request->file('logo');
            $nom = time(). uniqid() . '.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $nom);

            $company  = Company::find($request->company_id);

            if(isset($company->paramètres)){
                $company->paramètres->update([
                   'en_tete' => $request->en_tete,
                   'pied_page' => $request->pied_page,
                   'logo' => $nom 
                ]);
            } else {
                Paramètre::create([
                    'company_id' =>$request->company_id,
                    'en_tete' => $request->en_tete,
                    'pied_page' => $request->pied_page,
                    'logo' => $nom
                ]);
            }
            return redirect()->back();
            
        }
    }
}
