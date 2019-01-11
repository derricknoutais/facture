<template>
    <div class='container'>
        <!-- En-Tête -->
        <div class="row mt-6">
            <div class="col">
                <h1 class="text-center">Répertoire de {{ type }}</h1>
            </div>
        </div>
        <!-- Bouttons De Fonctionalité -->
        <div class="row mt-5">
            <div class="col"> 
                <span data-toggle="tooltip" :title="'Filtrer les' + ' ' + type + (type === 'Facture' ? 's': '' )"><button data-toggle="collapse" data-target="#filterCollapse" type="button" class="btn btn-primary"><i class="fas fa-filter"></i></button></span>   
            </div>
            <div class="col text-right">
                <div class="text-right">
                    <span data-toggle="tooltip" :title="'Créer ' +  (type === 'Facture' ? 'une nouvelle ': 'un nouveau ' ) + type "><button class="btn btn-primary" data-toggle="modal" data-target="#newDocument"><i class="fa fa-plus"></i></button></span>
                    <span data-toggle="tooltip" :title="'Supprimer '  + type + (type === 'Facture' ? 's': '' )"><a name="" class="btn btn-danger" href="#" role="button" data-toggle="modal" data-target="#confirmDeleteModal"><i class="fa fa-trash"></i></a></span>
                </div>
            </div>
        </div>
        <div class="row collapse" id="filterCollapse">
            <div class="row col-12 mt-3">
                <div class="col">
                    Par Client:
                    <select class="custom-select" v-model="filtrerPar.client">
                        <option value="0">Select All</option>
                        <option :value="client.id" v-for="client in clients">{{ client.nom + " " + client.prénom }}</option>
                    </select>
                </div>
                <div class="col">
                    Par Vendeur
                    <select class="custom-select" v-model="filtrerPar.vendeur">
                        <option value="0">Select All</option>
                        <option :value="vendeur.id" v-for="vendeur in vendeurs">{{ vendeur.name }}</option>
                    </select>
                </div>
                <div class="col">
                    Par État
                    <select class="custom-select" v-model="filtrerPar.etat">
                        <option selected disabled>Select one</option>
                        <option>Ouvert</option>
                        <option>E.A.V</option>
                        <option>Validé</option>
                        <option>Rejetté</option> 
                    </select>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary mt-4" @click="filtrer()" data-toggle="tooltip" title="Chercher">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-danger mt-4" @click="reinitialiserFiltres()" data-toggle="tooltip" title="Réinitialiser Filtres">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Tableau des Devis -->
        <div class="row mt-3">
            <div class="col">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="fit">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="" id="" v-model="isAllChecked" @change="toggleSelectAll()">
                                    </label>
                                </div>
                            </th>
                            <th>Numéro de Devis</th>
                            <th>Client</th>
                            <th>Vendeur</th>
                            <th>État</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(document, index) in localDocs" @click="redirectToDocument(document)"> 
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" v-model="itemsChecked[index]"> 
                                    </label>
                                </div>
                            </td>
                            <td><a :href="'/'+ document.company.name+'/' + type + '/' + document.id">{{ document.numéro }}</a></td>
                            <td>{{ document.client ? document.client.nom : 'N/A'}}</td>
                            <td>{{ document.créateur.name }}</td>
                            <td>{{ document.etat }}</td>
                            <td>
                                <div v-if="document.etat === 'E.A.V'">
                                    <button type="button" class="btn btn-primary btn-sm" @click="ajouterAValider(document)" data-toggle="tooltip" title="Valider">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" @click="ajouterARejetter(document)" data-toggle="tooltip" title="Rejeter">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal de Confirmation de Suppression  -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attention</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        {{ message }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" @click="rejetter()" v-if="this.aModifier.etat === 'Rejetté'">
                            Rejeter
                        </button>
                        <button type="button" class="btn btn-primary" @click="valider()" v-else-if="this.aModifier.etat === 'Validé'">
                            Valider
                        </button>
                        <button v-else type="button" class="btn btn-danger"  @click="deleteSelected()" >Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Supprimé avec Succès  -->
        <div class="modal fade" id="deletedSuccessfullyModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body p-5">
                        {{ message }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Error Modal  -->
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Attention</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        {{ this.message }}
                        <div class="row mt-5">
                            <div class="col">
                                <a class="btn btn-danger" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                  Plus D'information
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="collapse col" id="collapseExample">
                                {{ this.errorMsg }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Document -->
        <div class="modal fade" id="newDocument" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Créer {{ this.type === 'Facture'? 'Nouvelle' : 'Nouveau'}} {{ this.type }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div :class="isLoading ? 'spinner' : '' "></div>
                        <input class="form-control" disabled :value="newNumber">
                        <label></label>
                        <select class="form-control" v-model="newDocument.client">
                            <option v-for="client in clients" :value="client.id">{{ client.nom  + ' ' + client.prénom}}</option>
                        </select>
                        <div class="row mt-3">
                            <div class="col-2">
                                <label>Taxable?</label>
                            </div>
                            <div class="col">
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" v-model="newDocument.taxable" value="1">
                                  <label class="form-check-label" for="inlineRadio1">Oui</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" v-model="newDocument.taxable"  value="0">
                                  <label class="form-check-label" for="inlineRadio2">Non</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" @click="addNewDocument()">
                            Créer
                        </button>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: ['documents', 'type', 'clients', 'vendeurs', 'company'],
    data(){
        return {
            isAllChecked : false,
            isLoading: false,
            aModifier: {
                document: null,
                type: null
            },
            localDocs: [],
            selectedDocuments: [],
            itemsChecked: [],
            message: '',
            errorMsg: '',
            filtrerPar : {
                client: null,
                vendeur: null,
                état: null
            },
            newDocument: {
                client: null,
                taxable: 0,
                numero: null
            }
            
        }
    },
    computed:{
        newNumber(){
            let firstLetter = '';
            let number = null
            if(this.documents.length + 1 < 10 )
                number = '00' + (this.documents.length + 1)
            else if( this.documents.length + 1 < 100 )
                number = '0' + (this.documents.length + 1)
            else
                number = (this.documents.length + 1)
            
            this.newDocument.numero = (this.type.substring(0,1)  + number + '/' + new Date().getFullYear());            

            return (this.type.substring(0,1)  + number + '/' + new Date().getFullYear())
        }
    },
    methods:{
        displayModal(msg){
            $('#confirmDeleteModal').modal('hide')
            this.message = msg
            $('#deletedSuccessfullyModal').modal('show')
            setTimeout(() => {
                $('#deletedSuccessfullyModal').modal('hide')
                // location.reload()
            }, 3000);
        },
        displayErrorModal(msg, errorMsg){
            $('#confirmDeleteModal').modal('hide')
            this.message = msg
            this.errorMsg = errorMsg
            $('#errorModal').modal('show')
        },
        toggleSelectAll(){
            if(this.isAllChecked){
                $(':checkbox').each(function() {
                    this.checked = true;
                });
                this.documents.forEach( (document, index) => {
                    this.itemsChecked[index] = true
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
                this.documents.forEach( (element, index) => {
                    this.itemsChecked[index] = false
                });
            }
        },
        deleteSelected(){
            axios.post('/' + this.company.name + '/' + this.type + '/deleteSelectedDocuments', this.itemsChecked).then(response => {
                this.displayModal('Les éléments sélectionnés ont été supprimés avec succès')
                this.localDocs.forEach((document,index) => {
                    if(this.itemsChecked[index] === true){
                        this.localDocs.splice(index, 1)
                    }
                });
                this.$forceUpdate();
            }).catch(error => {
                this.displayErrorModal("Une erreur est survenue lors de la sauvegarde de la nouvelle entrée. S'il vous plaît, veuillez réessayer!", (error.message + '' + error.response.data.message) );
            });
        },
        setElementsCheckedToFalse(){
            this.documents.forEach((element, index) => {
                this.itemsChecked[index] = false;
            })
        },
        filtrer(){
            this.localDocs = this.documents
            if(this.filtrerPar.client !== null){
                this.localDocs = this.localDocs.filter( document => {
                    return document.client_id === this.filtrerPar.client
                })
            }
            if(this.filtrerPar.vendeur !== null){
                this.localDocs = this.localDocs.filter( document => {
                    return document.etabli_par === this.filtrerPar.vendeur
                })
            }
            if(this.filtrerPar.etat !== null){
                this.localDocs = this.localDocs.filter( document => {
                    return document.etat === this.filtrerPar.etat
                })
            }
        },
        reinitialiserFiltres(){
            this.filtrerPar.client = null
            this.filtrerPar.vendeur = null
            this.filtrerPar.etat = null
            this.localDocs = this.documents
        },
        ajouterARejetter(document){
            this.message = "Voulez-vous vraiment rejetter le devis Nº" + document.numéro + ". Cette action est irréversible";
            this.aModifier.document = document
            this.aModifier.etat = "Rejetté";
            this.$forceUpdate()
            $('#confirmDeleteModal').modal('show')
            
        },
        ajouterAValider(document){
            this.message = "Voulez-vous vraiment rejetter le devis Nº" + document.numéro + ". Cette action est irréversible";
            this.aModifier.document = document
            this.aModifier.etat = "Validé";
            this.$forceUpdate()
            $('#confirmDeleteModal').modal('show')
            
        },
        rejetter(){
            axios.post('/' + this.company.name + '/' + this.type + '/rejetter', this.aModifier).then(response => {
                this.displayModal('Le Devis Nº' + this.aModifier.document.numéro +  ' ont été supprimés avec succès')
                this.aModifier.document = null;
                this.aModifier.etat = null;
            }).catch(error => {
                this.displayErrorModal("Une erreur est survenue lors du rejet de la nouvelle entrée. S'il vous plaît, veuillez réessayer!", (error.message + '' + error.response.data.message) );
            });
        },
        valider(){
            axios.post('/' + this.company.name + '/' + this.type + '/rejetter', this.aModifier).then(response => {
                this.displayModal('La' + this.type + 'Nº' + this.aModifier.document.numéro +  ' ont été supprimés avec succès')
                this.aModifier.document = null;
                this.aModifier.etat = null;
            }).catch(error => {
                this.displayErrorModal("Une erreur est survenue lors du rejet de la nouvelle entrée. S'il vous plaît, veuillez réessayer!", (error.message + '' + error.response.data.message) );
            });
        },
        addNewDocument(){
            axios.post('/' + this.company.name + '/' + this.type + '/store', {company: this.company.id, document: this.newDocument}).then(response => {
                $('#newDocument').modal('hide');
                // console.log(response.data.id)
                window.location.href = '/' + this.company.name + '/' + this.type + '/' + response.data.id;
            }).catch(error => {
                console.log(error);
            });
        },
        redirectToDocument(document){
            window.location = '/'+ document.company.name+'/' + this.type + '/' + document.id ;
        }
    },
    mounted(){
        this.localDocs = this.documents;
        console.log()
    },
    created(){
        this.setElementsCheckedToFalse();
        axios.interceptors.request.use((config) => {
            this.isLoading = true
            return config;
        }, (error) => {
            // this.isLoading = false
            return Promise.reject(error);
        });
        axios.interceptors.response.use((response) => {
            // this.isLoading = false
            return response;
        }, (error) => {
            // this.isLoading = false
            return Promise.reject(error);
        });
    }
}
</script>
<style>
.no-border{
      border: none!important;
}
.table td.fit, 
.table th.fit {
    white-space: nowrap;
    width: 1%;
}
.scroller {
    overflow-y: scroll;
}
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