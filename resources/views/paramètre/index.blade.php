@extends('layouts.app', compact('company'))

@section('content')
    <div class="container">
		<form action="/maj-paramètres" method="POST" enctype="multipart/form-data">
			@csrf
			<h2>Paramètres</h2>

			<div class="form-group mt-5">
					<label for="">Télécharger votre logo</label>
					<input type="file" name="logo" class="form-control-file" aria-describedby="fileHelpId" >
					
			</div> 
			<input type="hidden" name="company_id" value="{{ $company->id }}">

			<div class="form-group mt-5">
					<label for="">Texte En tête Facture</label>
					<textarea type="text" class="form-control" rows=3 name="en_tete">
							@if ($paramètres)
								{{ $paramètres->en_tete }}
							@endif

					</textarea>
			</div>

			<div class="form-group mt-5">
					<label for="">Pied de Page</label>
					<textarea type="text" class="form-control" rows=3 name="pied_page">
							@if ( $paramètres )
								{{ $paramètres->pied_page }}
							@endif

					</textarea>
			</div>
			<button type="submit" class="btn btn-primary">Enregistrer</button>
		</form>
        


        
    </div>
@endsection