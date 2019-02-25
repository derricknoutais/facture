@extends('layouts.app') 

@section('content')
    <show-document :document="{{ $facture }}" type="Facture" :clients="{{ $clients }}" :user="{{ $user }}"></show-document>
@endsection