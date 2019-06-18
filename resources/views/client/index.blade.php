@extends('layouts.app')


@section('content')
    <index-client :clients='{{ $clients }}' :company="{{ $company}}" inline-template>
        <div class='container'>

            <!-- En-tête -->
            <div class="row mt-6">
                <div class="col">
                    <h1 class="text-center">Répertoire Clients</h1>
                </div>
            </div>

            <!-- Boutons de Fonctionnalité -->
            <div class="row mt-5">
                <div class="col text-right">
                    <span data-toggle="tooltip" title="Créer Nouveau Client"><a href="/send-mail" class="btn btn-primary"><i class="fas fa-envelope"></i></a></span>
                    <span data-toggle="tooltip" title="Créer Nouveau Client"><button class="btn btn-primary" data-toggle="modal" data-target="#newClient"><i class="fa fa-plus"></i></button></span>
                </div>
            </div>

            <!-- Tableau Liste Des Clients -->
            <div class="row mt-3">
                <div class="col">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(client, index) in clientLocal">
                                <td scope="row"> 
                                    <a :href="'/' + company.name + '/Client/' + client.id">@{{ client.nom + ' ' + ( client.prénom ? client.prénom : '' ) }}</a>
                                </td>
                                <td>
                                    <a href=""></a>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" :data-target="'#client' + index">
                                        <i class="fa fa-edit text-white"></i>
                                    </button>

                                    <!-- Mise à Jour Client  -->
                                    <div class="modal fade" :id="'client' + index" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form class="modal-content" :action="'/' + company.name + '/Client/' + client.id + '/update'" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Mettre à Jour Information</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="">Nom</label>
                                                        <input type="text" class="form-control"  :value="client.nom" name="nom">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Prénom</label>
                                                        <input type="text" class="form-control"  :value="client.prénom" name="prénom">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Numéro de Téléphone</label>
                                                        <input type="text" class="form-control"  :value="client.numéro" name="numéro">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Adresse</label>
                                                        <textarea class="form-control" rows="5" v-if="client.addresse" name="addresse">
                                                        @{{ client.addresse }}
                                                        </textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" class="btn btn-primary" @click="updateClient()">Editer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- New Client -->
            <div class="modal fade" id="newClient" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">

                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Créer Nouveau Client</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div :class="isLoading ? 'spinner' : '' "></div>
                            <label>Nom:</label>
                            <input class="form-control" v-model="newClient.nom">
                            <label>Prénom:</label>
                            <input class="form-control" v-model="newClient.prenom">
                            <label>Addresse</label>
                            <textarea class="form-control" v-model="newClient.addresse"></textarea>
                            <label>Numéro:</label>
                            <input class="form-control" v-model="newClient.numéro">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" @click="créerClient()">Créer</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </index-client>
@endsection