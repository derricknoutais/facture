@extends('layouts.app')


@section('content')
    <show-document :document="{{ $devis }}" type="Devis" :clients="{{ $clients }}"></show-document>
@endsection