@extends('layouts.app') 

@section('content')
    <show-document :document="{{ $facture }}" type="Facture" :clients="{{ $clients }}"></show-document>
@endsection