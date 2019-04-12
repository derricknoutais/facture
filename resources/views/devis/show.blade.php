@extends('layouts.app' , ['title' => $title])


@section('content')
    <show-document :company="{{ $company }}" :document="{{ $devis }}" type="Devis" :clients="{{ $clients }}" :documents="{{ $factures }}" :user="{{ $user }}"></show-document>
@endsection