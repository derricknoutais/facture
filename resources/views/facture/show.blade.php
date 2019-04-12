@extends('layouts.app') 

@section('content')
    <show-document 
        :document="{{ $facture }}" type="Facture" 
        :clients="{{ $clients }}" :user="{{ $user }}"
        :company="{{ $company }}"
    ></show-document>
@endsection