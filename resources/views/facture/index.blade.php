@extends('layouts.app') 

@section('content')
<index-document :documents="{{ $facture }}" type="Facture" :clients="{{ $clients }}" :vendeurs="{{ $vendeurs }}" :company="{{ $company }}"></index-document>
@endsection