<template>
    <div class='container'>
        <div class="row mt-6">
            <div class="col">
                <h1 class="text-center">Répertoire Clients</h1>
            </div>
        </div>
        
        <!-- Boutons de Fonctionnalité -->
        <div class="row mt-5">
            <div class="col text-right">
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="client in clientLocal">
                            <td scope="row"> 
                                <a :href="'/' + company.name + '/Client/' + client.id">{{ client.nom + ' ' + client.prénom }}</a>
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
</template>

<script>
export default {
    props: ['clients', 'company'],
    data(){
        return {
            newClient: {
                nom: null,
                prenom: null,
                addresse: null,
                numéro: null
            },
            newCompany : {

            },
            isLoading: false,
            clientLocal: null
        }
    },
    methods:{
        créerClient(){
            axios.post('/' + this.company.name + '/Client/store', this.newClient ).then( response => {
                this.newClient.prénom = this.newClient.prenom;
                this.clientLocal.push(this.newClient)
                this.$forceUpdate();
                $('#newClient').modal('hide')
            }).catch(error => {
                console.log(error);
            });
        }
    },
    mounted(){
        // console.log(this.$root.$data.isLoading)
        this.clientLocal = this.clients;
    },
    created(){
        axios.interceptors.request.use((config) => {
            this.isLoading = true
            return config;
        }, (error) => {
            this.isLoading = false
            return Promise.reject(error);
        });
        axios.interceptors.response.use((response) => {
            this.isLoading = false
            return response;
        }, (error) => {
            this.isLoading = false
            return Promise.reject(error);
        });
    }
}
</script>
<style>
    .spinner {
        position: absolute;
        left: 44%;
        top: 43%;
        height:60px;
        width:60px;
        margin:0px auto;
        -webkit-animation: rotation .6s infinite linear;
        -moz-animation: rotation .6s infinite linear;
        -o-animation: rotation .6s infinite linear;
        animation: rotation .6s infinite linear;
        border-left:6px solid rgba(0,174,239,.15);
        border-right:6px solid rgba(0,174,239,.15);
        border-bottom:6px solid rgba(0,174,239,.15);
        border-top:6px solid rgba(0,174,239,.8);
        border-radius:100%;
    }
    @-webkit-keyframes rotation {
        from {-webkit-transform: rotate(0deg);}
        to {-webkit-transform: rotate(359deg);}
    }
    @-moz-keyframes rotation {
        from {-moz-transform: rotate(0deg);}
        to {-moz-transform: rotate(359deg);}
    }
    @-o-keyframes rotation {
        from {-o-transform: rotate(0deg);}
        to {-o-transform: rotate(359deg);}
    }
    @keyframes rotation {
        from {transform: rotate(0deg);}
        to {transform: rotate(359deg);}
    }
</style>