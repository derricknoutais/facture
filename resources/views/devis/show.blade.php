@extends('layouts.app' , ['title' => $title])


@section('content')
    <show-document :document="{{ $devis }}" type="Devis" :clients="{{ $clients }}" :documents="{{ $factures }}" :user="{{ $user }}"></show-document>
@endsection