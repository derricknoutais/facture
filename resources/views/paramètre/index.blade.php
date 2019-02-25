@extends('layouts.app', compact('company'))

@section('content')
    <div class="container">
        <h2>Paramètres</h2>

        <div class="form-group mt-5">
          <label for="">Télécharger votre logo</label>
          <input type="file" class="form-control-file" aria-describedby="fileHelpId" >
        </div> 

        <div class="form-group mt-5">
          <label for="">Texte En tête Facture</label>
          <textarea type="text" class="form-control" rows=3>{{ $paramètres->en_tete }}
          </textarea>
        </div>

        <div class="form-group mt-5">
          <label for="">Pied de Page</label>
          <textarea type="text" class="form-control" rows=3>{{ $paramètres->pied_page }}
          </textarea>
        </div>


        <button type="button" class="btn btn-primary">Enregistrer</button>
    </div>
@endsection