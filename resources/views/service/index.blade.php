@extends('layouts.app')


@section('content')
<div class="container">
    <h1 class="text-center">Répertoire des Services</h1>
    <div class="row my-5">
        <div class="col">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId">
              Ajouter Un Service
            </button>
            
            <!-- Modal -->
            <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <form action="/{{ $company->name }}/Services/store" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="">Référence</label>
                                  <input type="text" class="form-control" name="référence" >
                                </div>
                                <div class="form-group">
                                  <label for="">Désignation</label>
                                  <input type="text" class="form-control" name="désignation" >
                                </div>
                                <div class="form-group">
                                  <label for="">Prix</label>
                                  <input type="text" class="form-control" name="prix" >
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary" >Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Désignation</th>
                <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)    
                <tr>
                    <td scope="row">{{ $service->référence }}</td>
                    <td>{{ $service->désignation }}</td>
                    <td>{{ $service->prix }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection