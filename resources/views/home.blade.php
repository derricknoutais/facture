@extends('layouts.app')

@section('content')
<div class="jumbotron jumbotron-fluid">
    <div class="container text-center">
        <div class="row text-center mb-5">
            <div class="col">
                <img src="/img/logo.png" width="500vw">
            </div>
        </div>
        <h1 class="display-5 text-center">Créer un Compte Pour Gérer vos Factures</h1>

        <p class="lead text">Un Agent Validera votre demande dans les plus Brefs Délais</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="/register" role="button">Créer un Compte</a>
            <a class="btn btn-primary btn-lg" href="/login" role="button">Se Connecter</a>
        </p>
        
    </div>
    
</div>


@endsection
