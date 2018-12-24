@extends('layouts.app')


@section('content')
    <index-client :clients='{{ $clients }}' :company="{{ $company}}"></index-client>
@endsection