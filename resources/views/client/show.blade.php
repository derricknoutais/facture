@extends('layouts.app')


@section('content')
    <show-client :client="{{ $client }}" :company="{{ $company }}"></show-client>
@endsection