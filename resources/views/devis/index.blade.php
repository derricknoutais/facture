@extends('layouts.app')


@section('content')
    <index-document :documents="{{ $devis }}" type="Devis" :clients="{{ $clients }}" :vendeurs="{{ $vendeurs }}"></index-devis>
@endsection