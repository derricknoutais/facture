@extends('layouts.app')


@section('content')
    <caisse-index :company="{{ $company }}" :caisse="{{ $company->caisse() }}"></caisse-index>
@endsection