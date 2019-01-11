@extends('layouts.app')


@section('content')
    <nav class="nav nav-pills justify-content-around nav-fill">
        <div class="nav nav-tabs w-100 text-right" id="nav-tab" role="tablist">
            @foreach (Auth::user()->companies as $company)
                <a  

                    @if($loop->first) 
                        class="nav-item nav-link active" 
                    @else 
                        class="nav-item nav-link" 
                    @endif
                    
                    data-toggle="tab" href="#company{{ $company->id }}" 
                    role="tab" aria-controls="nav-home"
                    aria-selected="true">{{ $company->name }}
                </a>
            @endforeach

        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        @foreach (Auth::user()->companies as $company)
            <div 
                @if($loop->first) class="tab-pane fade show active" @else class="tab-pane fade" @endif
                id="company{{ $company->id }}" role="tabpanel" aria-labelledby="nav-profile-tab">
                <company-home :company="{{ $company }}"></company-home>
            </div>
            
        @endforeach
    </div>
@endsection