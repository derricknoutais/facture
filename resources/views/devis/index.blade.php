@extends('layouts.app')


@section('content')
<index-document :documents="{{ $devis }}" type="Devis" :clients="{{ $clients }}" :vendeurs="{{ $vendeurs }}" :company="{{ $company }}"></index-devis>
@endsection