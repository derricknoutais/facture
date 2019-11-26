<template>
    <div class='container' style="margin-top : 0">
        <!-- En-tête  -->
        <div class="row">
            <div class="col text-center">
                <img v-if="company.paramètres" :src="'/uploads/' + company.paramètres.logo " alt="" class="text-center img-fluid" width="200vw">
                <a :href="'/' + company.name + '/paramètres'" v-else><img  src='/img/télécharger.png' alt="" class="text-center img-fluid" width="300vw" ></a>
            </div>
        </div>
        
        <h2 class="text-center mt-5" v-if="company.paramètres">{{ company.paramètres.en_tete_ligne1 }}</h2>
        <h2 class="text-center mt-5" v-if="company.paramètres">{{ company.paramètres.en_tete_ligne2 }}</h2>
        <h2 class="text-center mt-5" v-if="company.paramètres">{{ company.paramètres.en_tete_ligne3 }}</h2>
        
        <!-- Info Client -->
        <div class="row" v-if="printing">
            <div class="col-12">
                <h5 class="text-right">Fait à Port-Gentil, le {{ document.date | moment('DD MMMM YYYY') }}</h5>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-8" v-if="infoEditMode === false && ! printing">
                <p><strong><u>Date:</u></strong> {{ document.date | moment('DD/MM/YYYY') }}</p>
                <p>Nº {{ type }}: {{ document.numéro }}</p>
                <p>Vendeur: {{ document.créateur.name }}</p>
                <p v-if="document.objet !== null">Objet: {{ document.objet }}</p>
                <p v-if="document.échéance !== null  && this.type === 'Facture'">Échéance: {{ document.échéance }}</p>
                <p v-if="type === 'Facture' && (validé || eap)"><a href="#" data-toggle="modal" data-target="#modalPayements"><strong>{{ this.document.payements.length }} {{ this.document.payements.length > 1 ? 'Payements Reçus: ' : 'Payement Reçu: '}}</strong></a> {{ totalPayements | currency}}</p>
            </div>
            <div class="col-8" v-if="infoEditMode === true">
                <!-- Date -->
                <div class="col">
                    <div class="input-group mb-3">
                        <input type="date" class="form-control" placeholder="Vendeur" v-model="infos.date">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" @click="saveInfo()">
                                <i class="fa fa-save"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Objet -->
                <div class="col">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Objet" v-model="infos.objet">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" @click="saveInfo()">
                                <i class="fa fa-save"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Échéance -->
                <div class="col" v-if="this.type === 'Facture'">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Échéance" v-model="infos.échéance">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" @click="saveInfo()">
                                <i class="fa fa-save"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Si On est Pas En Mode Edit, Display Info Client -->
            <div :class=" printing ? 'offset-8': '' " class="col-4 text-center border border-dark px-5 pt-4" v-if="infoEditMode === false && document.client !== null" style="padding-left:50%">
                <h4 class="d-inline-block float-left mt-2"><strong><u>Client:</u></strong></h4>
                <p class="mt-5 text-left">
                    <a :href="'/' + company.name + '/Client/' + document.client.id">
                    {{ document.client.nom + ' ' + (document.client.prénom ? document.client.prénom : '') }}
                    </a>
                </p>
                <p class="text-left">{{ (document.client.numéro ? document.client.numéro : '') }}</p>
                <p class="text-left">{{ document.client.addresse ? document.client.addresse : ''}}</p>
            </div>
            <!-- Sinon Display Le Formulaire Pour Choisir Un Client -->
            <div class="col-3 text-center border border-primary px-5 py-4" v-else>
                <h5 class="d-inline-block float-left mt-2"><u>Client:</u></h5>
                <div class="input-group">
                    <select class="custom-select" v-model="infos.client_id">
                        <option selected>Client...</option>
                        <option :value="client.id" v-for="client in clients">{{ client.nom + ' ' + (client.prénom ? client.prénom : '') }}</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" @click="saveInfo()"><i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- Infos Document -->

        <div class="row mt-5" v-if="!validé && !payé && !printing" >
            <!-- Date -->
            <div class="col" v-if="document.date === null">
                <label>Date</label>
                <div class="input-group mb-3">
                    <input type="date" class="form-control" placeholder="Vendeur" v-model="infos.date" @keyup.enter="saveInfo()">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" @click="saveInfo()">
                            <i class="fa" :class="isLoading ? 'fa-spinner' : ' fa-save'"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Objet -->
            <div class="col" v-if="document.objet === null">
                <label>Objet</label>
                <div class="input-group mb-3" >
                    <input type="text" class="form-control" placeholder="Objet" v-model="infos.objet" @keyup.enter="saveInfo()">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" @click="saveInfo()">
                            <i class="fa" :class="isLoading ? 'fa-spinner' : 'fa-save'"></i>
                        </button>

                    </div>
                </div>
            </div>  
            <!-- Échéance -->
            <div class="col" v-if="this.type === 'Facture' && document.échéance === null">
                <label>Date d'échéance</label>
                <div class="input-group mb-3">
                    <input type="date" class="form-control" placeholder="Échéance" v-model="infos.échéance" @keyup.enter="saveInfo()">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" @click="saveInfo()">
                            <i class="fa" :class="isLoading ? 'fa-spinner' : ' fa-save'" ></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouttons de fonctionnalité  -->
        <div class="row mt-5" v-if="! printing" >
            <div class="col text-right" v-if="document.etat !== 'Rejetté'">
                    
                    <!-- Créer Facture -->
                    <span data-toggle="tooltip" title="Créer Facture">
                        <button data-toggle="modal" data-target="#devisToFactureModal" class="btn btn-primary" v-if="this.type==='Devis' && validé && manager">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </button>
                    </span>

                    <!-- Ajouter Payement -->
                    <span data-toggle="tooltip" title="Ajouter Payement"><button data-toggle="modal" data-target="#payementModal" class="btn btn-primary" v-if="this.type==='Facture' && (validé || eap || pp) && manager">
                            <i class="far fa-money-bill-alt"></i>
                        </button>
                    </span>

                    <!-- Valider Facture -->
                    <span data-toggle="tooltip" title="Valider"><button type="button" class="btn btn-primary" v-if="(eav && manager) || (this.type === 'Devis' && agent)" @click="ajouterAValider(document)">
                            <i class="fas fa-check"></i>
                        </button>
                    </span>

                    <!-- Rejetter Facture -->
                    <span data-toggle="tooltip" title="Rejetter">
                        <button type="button" class="btn btn-danger" v-if="(eav && manager) || (this.type === 'Devis' && agent)" @click="ajouterARejetter(document)" >
                            <i class="fas fa-times"></i>
                        </button>
                    </span>

                    <!-- Imprimer Document  -->
                    <span data-toggle="tooltip" title="Imprimer" id="">
                        <button class="btn btn-info text-white" @click="printDocument()">
                            <i class="fas fa-print"></i>
                        </button>
                    </span>

                    <!-- Ajouter Ligne -->
                    <button name="" class="btn btn-primary" v-if="!validé && !eap && !payé" @click="addLine()" data-toggle="tooltip" title="Ajouter Ligne"><i class="fa fa-plus"></i></button>
                    
                    <!-- Editer -->
                    <button id="editButton" v-if="this.editMode === false && this.infoEditMode === false && !validé && !eap && !payé " class="btn btn-secondary" @click="editSelected()" data-toggle="tooltip" title="Modifier"><i class="fa fa-edit"></i></button></span>
                    
                    <!-- Sauvegarder Modif -->
                    <button name="" v-if="this.editMode === true" class="btn btn-primary" @click="updateSelected()" data-toggle="tooltip" title="Sauvegarder Modifications"><i class="fa fa-save"></i></button>
                    <button name="" v-if="this.infoEditMode === true" class="btn btn-primary" @click="saveInfo()" data-toggle="tooltip" title="Sauvegarder Modifications"><i class="fa fa-save"></i></button>
                    
                    <!-- Supprimer  -->
                    <span  data-toggle="tooltip" title="Supprimer Lignes"><a name="" v-if="!validé && !eap && !payé" :class="this.itemsChecked ? 'btn btn-danger' : 'btn btn-danger'" href="#" role="button" data-toggle="modal" data-target="#confirmDeleteModal"><i class="fa fa-trash"></i></a></span>
            </div>
        </div>
        <!-- En-Tête -->
        <div :class="document.etat === 'Rejetté' ? 'rejetté' : '' " style="height:100vh">
        <div class="row mt-3">
            <div class="col">
                <h1 class="text-left">{{ type }} Nº {{ document.numéro }}</h1>
            </div>  
        </div>
        <div class="row mt-3">
            <div class="col">
                <h5 class="text-left "><strong><u>Objet:</u></strong>  {{ document.objet }}</h5>
            </div>  
        </div>
        <!-- Tableau des Entrées du Document  -->      
        <table class="table table-bordered table-fit no-border mt-5">
            <thead>
                <tr class="">
                    <th class="checkButtons" v-if="! printing">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="" id="" v-model="isAllChecked" @change="toggleSelectAll()">
                            </label>
                        </div>
                    </th>
                    <th>Quantité</th>
                    <th>Description</th>
                    <th>Prix Unitaire</th>
                    <th>Prix Total</th>
                </tr>
            </thead>
            <tbody>
                <!-- Entrées -->
                <tr v-for="(entree, index) in document.entrees">
                    <td class="checkButtons" v-if="! printing && entree.edit === false ">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" v-model="itemsChecked[index]">
                            </label>
                        </div>
                    </td>
                    
                    <td v-if="entree.edit === true">
                        <!-- <select class="form-control" v-model="newService" @change="addServiceInfo(index)">
                            <option v-for="service in company.services" :value="service">{{ service.référence }}</option>
                        </select> -->
                        
                    </td>

                    <td scope="row" v-if="entree.edit === false">{{ entree.quantité }}</td>
                    <td v-if="entree.edit === true"><input class="form-control" v-model="entree.quantité"></td>

                    <td v-if="entree.edit === false">{{ entree.description }}</td>
                    <td v-if="entree.edit === true"><input class="form-control" v-model="entree.description"></td>

                    <td v-if="entree.edit === false">{{ entree.prix_unitaire | currency }}</td>
                    <td v-if="entree.edit === true"><input class="form-control" v-model="entree.prix_unitaire"></td>
                    <td><b>{{ entree.quantité * entree.prix_unitaire  | currency}}</b></td>
                </tr>
                <!-- Nouvelles Entrées (Ajouter Entrées) -->
                <tr v-if="nouvellesEntrées" v-for="(nouvellesEntrée, index) in nouvellesEntrées">
                    <td>
                        <!-- <select class="form-control" v-model="newService" @change="addServiceInfo(index)">
                            <option v-for="service in company.services" :value="service">{{ service.référence }}</option>
                        </select> -->
                        <multiselect 
                            v-model="newService" :options="company.services" :searchable="true"  
                            label="ref" placeholder="Pick a value" @select="addServiceInfo(index)" selectLabel="" selectedLabel="ss">
                        </multiselect>
                    </td>
                    <td><input class="form-control" v-model.number="nouvellesEntrée.quantité"></td>
                    <td>
                        <input class="form-control" v-model="nouvellesEntrée.description">
                        <!-- <multiselect 
                            v-model="newService" :options="company.services" :searchable="true" 
                            label="désignation" placeholder="Pick a value" @select="addServiceInfo(index)">
                        </multiselect> -->
                    </td>
                    <td><input class="form-control" v-model.number="nouvellesEntrée.prix_unitaire"></td>
                    <td>
                        <button name="" class="btn btn-primary" @click="saveLine(index)"><i class="fa fa-save"></i></button>
                        <button name="" class="btn btn-danger" href="#" role="button" @click="removeNewLine(index)"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <!-- Totaux -->
                <tr class="no-border" v-if="this.document.entrees.length > 0 " >
                    <td :colspan=" printing ? '2' : '3' " class="no-border checkButtons"></td>
                    <td class="text-right border-left " >Sous-Total</td>
                    <td class=""><b>{{ subtotal | currency}}</b></td>
                </tr>
                <tr v-if="this.document.entrees.length > 0 && this.document.taxable">
                    <td :colspan=" printing ? '2' : '3' " class="no-border checkButtons" :class="printing ? '': 'no-border' "></td>
                    <td class="text-right border-left">TVA -9.5%</td>
                    <td class=""> <b>{{ tva | currency }}</b></td>
                </tr>
                <tr v-if="this.document.entrees.length > 0">

                    <td :colspan=" printing ? '2' : '3' " class="no-border checkButtons" :class="printing ? '': 'no-border' "></td>

                    <!-- Si des paiements ont été reçus présente le total -->
                    
                    <td class="text-right border-left border-bottom text-primary" v-if="this.document.payements && this.document.payements.length > 0">{{this.document.payements.length}} Paiement(s) Reçu(s)</td>
                    <td class="border-bottom text-primary" v-if="this.document.payements && this.document.payements.length > 0"><b>{{ this.totalPayements | currency }}</b></td>

                    <!-- Sinon juste présente le grand total  -->

                    <td class="text-right border-left border-bottom" v-if=" (this.document.payements && this.document.payements.length === 0) || type === 'Devis'">Grand Total</td>
                    <td class="border-bottom" v-if=" ( this.document.payements && this.document.payements.length === 0 ) || type === 'Devis'"><b>{{ grandTotal | currency }}</b></td>

                </tr>
                <tr v-if="this.document.payements && this.document.payements.length > 0">
                    <td :colspan=" printing ? '2' : '3' " class="no-border checkButtons"></td>

                    <!-- <td class="text-right border-left border-bottom" v-if="document.type === 'Facture' && document.payements.length === 0 ">Grand Total</td> -->
                    <td class="text-right border-left border-bottom text-danger" >Reste à Payer</td>
                    <td class="border-bottom text-danger"><b>{{ subtotal - this.totalPayements | currency }}</b></td>
                </tr>
            </tbody>
        </table>

        <h6 class="mt-5" v-if="grandTotal !== 0">Arrêté {{ type === 'Facture' ? 'la présente ' : 'le présent ' }} {{ type }} à la somme de:</h6>


        <h5 class="mt-3" v-if="grandTotal !== 0"><strong>{{ wn(this.grandTotal) }} Francs CFA</strong></h5>


        <h5 class="text-right">Le Responsable</h5>
        <h5 class="text-center mt-3" v-if="company.paramètres">{{ company.paramètres.pied_page }}</h5>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <button type="button" class="btn btn-success px-5 py-1" v-if="ouvert" @click="ajouterAEAV(document)">
                    <i class="fas fa-save"></i>
                </button>
            </div>

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
                        <button type="button" class="btn btn-danger" @click="rejetter()" v-if="this.aModifier.etat === 'Rejetté'">
                            Rejeter
                        </button>
                        <button type="button" class="btn btn-primary" @click="valider()" v-else-if="this.aModifier.etat === 'Validé' || this.aModifier.etat === 'E.A.V' || this.aModifier.etat === 'E.A.P'">
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

        <!-- Payement Modal  -->
        <div class="modal fade" id="payementModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Effectuer Payement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row flex justify-content-between px-3">
                            <p><strong>Client:</strong></p>
                            <p><strong>Montant Facture:</strong></p>
                        </div>
                        <div class="row flex justify-content-between px-3 mt-0">
                            <p v-if="document.client">{{ document.client.nom + ' ' + (document.client.prénom ? document.client.prénom : '') }}</strong></p>
                            <p>{{ grandTotal | currency }}</p>
                        </div>
                        <div class="row flex justify-content-between px-3" v-if="this.type === 'Facture'">
                            <p  v-if="type === 'Facture' && validé"><strong>{{ this.document.payements.length }} {{ this.document.payements.length > 1 ? 'Payements Reçus' : 'Payement Reçu'}} </strong></p>
                            <p><strong>Reste à Payer</strong></p>
                        </div>
                        <div class="row flex justify-content-between px-3 mt-0">
                            <p><strong>{{ totalPayements | currency }}</strong></p>
                            <p class="text-danger"><strong>{{ grandTotal - totalPayements - payment.montant| currency }}</strong></p>
                        </div>
                        <div class="row flex justify-content-center mt-3">
                            <div class="form-group col-9">
                                <label for="">Numéro Facture</label>
                                <select class="custom-select disabled" v-model="payment.facture_id">
                                    <option :value="document.id">{{ document.numéro }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row flex justify-content-center">
                            <div class="form-group col-9">
                                <label for="">Montant Payement</label>
                                <input type="text" class="form-control" v-model="payment.montant">
                            </div>
                        </div>
                        <div class="row flex justify-content-center">
                            <div class="form-group col-9">
                                <label for="">Note</label>
                                <input type="text" class="form-control" v-model="payment.note">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger"  @click="addPayment()">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des Payements -->
        <div v-if="this.type ==='Facture'" class="modal fade" id="modalPayements" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Liste Payement</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Note</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="payement in document.payements">
                                    <td>{{ payement.created_at }}</td>
                                    <td>{{ payement.note }}</td>
                                    <td>{{ payement.montant | currency }}</td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devis en Facture Modal  -->
        <div v-if="this.type ==='Devis'" class="modal fade" id="devisToFactureModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Devis ==> Facture</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        Êtes-vous sûr de créer une Facture pour ce Devis?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger"  @click="devisToFacture()">Créer Facture</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- En-Tête -->
        <div class="footer">
            Agrément Nº 0000010735/MCDI/DGC Nº Stat 231214 X Compte BICIG Nº40001 09085 40007 100072
        </div>
        
    </div>
</template>

<script>
export default {
    props:['document', 'type', 'clients', 'documents', 'user', 'company'],
    data(){
        return {
            value: null,
            options: ['Select option', 'options', 'selected', 'mulitple', 'label', 'searchable', 'clearOnSelect', 'hideSelected', 'maxHeight', 'allowEmpty', 'showLabels', 'onChange', 'touched'],
            isAllChecked: false,
            editMode: false,
            infoEditMode: false,
            isTaxable18: true,
            isTaxableCss: true,
            isLoading: false,
            itemsChecked: [],
            nouvellesEntrées: [],
            entrees: [],
            message: '',
            errorMsg: '',
            infos: {
                client_id: null,
                date: null,
                objet: null,
                échéance: null
            },
            payment: {
                facture_id: null,
                montant: null,
                note: null
            },
            aModifier: {
                document: null,
                type: null
            },
            newFacture : {
                client: null,
                taxable: 0,
                numero: null,
                entrees: [],
            },
            newService : null,
            printing: false
        }
    },
    computed: {
        subtotal(){
            let sub = 0;
            this.entrees.forEach(element => {
                sub += (element.quantité * element.prix_unitaire)
            })
            return sub;
        },
        // Calcule La Taxe 18%
        tva(){
            if(this.isTaxable18 && this.document.taxable)
                return Math.ceil(this.subtotal * 0.095)
            else 
                return 0
        },
        grandTotal(){
            return this.subtotal - this.tva
        },
        totalPayements(){
            if(this.type === "Facture"){
                let total = 0;
                this.document.payements.forEach(payement => {
                    total += payement.montant
                })
                return total;
            } 
        },
        validé(){
            if(this.document.etat === 'Validé' || this.eap || this.pp ){
                return true
            } else {
                return false
            }
        },
        eav(){
            if(this.document.etat === 'E.A.V'){
                return true
            } else {
                return false
            }
        },
        eap(){
           if(this.document.etat === 'E.A.P'){
               return true
           } else {
               return false
           } 
        },
        ouvert(){
            if(this.document.etat === 'Ouvert'){
                return true
            } else {
                return false
            }
        },
        pp(){
           if(this.document.etat === 'Paiement Partiel'){
               return true
            } else {
               return false
            }
        },
        payé(){
           if(this.document.etat === 'Payé'){
              return true
           } else {
              return false
           } 
        },
        newNumber(){
            let firstLetter = '';
            let number = null

            if(this.documents.length + 1 < 10 )
                number = '00' + (this.documents.length + 1)
            else if( this.documents.length + 1 < 100 )
                number = '0' + (this.documents.length + 1)
            else
                number = (this.documents.length + 1)

            this.newFacture.numero = (this.type.substring(0,1)  + number + '/' + new Date().getFullYear());            

            return (this.type.substring(0,1)  + number + '/' + new Date().getFullYear())
        },
        manager(){
            if(this.user.role === 'Manager'){
                return true
            } else {
                return false
            }
        },
        agent(){
            if(this.user.role === 'Agent'){
                return true
            } else {
                return false
            }
        }
    },
    watch: {
        newService(){
            // console.log(this.newService);
            
        }
    },
    methods:{
        displayModal(msg){
            $('#confirmDeleteModal').modal('hide')
            this.message = msg
            $('#deletedSuccessfullyModal').modal('show')
            setTimeout(() => {
                $('#deletedSuccessfullyModal').modal('hide')
                location.reload()
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
                this.document.entrees.forEach( (element, index) => {
                    this.itemsChecked[index] = true
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
                this.document.entrees.forEach( (element, index) => {
                    this.itemsChecked[index] = false
                });
            }
        },
        addLine(){
            var nouvelleLigne = new Object();
            nouvelleLigne.devis_id = this.document.id
            nouvelleLigne.edit = false
            nouvelleLigne.quantité = 1
            nouvelleLigne.description = ''
            nouvelleLigne.prix_unitaire = 0

            this.nouvellesEntrées.push(
                nouvelleLigne
            )
            this.$forceUpdate();
        },

        removeNewLine(index){
            this.nouvellesEntrées.splice(index,1);
        },
        addServiceInfo(index){
            setTimeout(() => {
                this.nouvellesEntrées[index].description = this.newService.désignation
                this.nouvellesEntrées[index].quantité = 1
                this.nouvellesEntrées[index].prix_unitaire = this.newService.prix
                this.$forceUpdate();
            }, 100);
            
        },
        editServiceInfo(index){
            setTimeout(() => {
                this.document.entrees[index].description = this.newService.désignation
                this.document.entrees[index].quantité = 1
                this.document.entrees[index].prix_unitaire = this.newService.prix
                this.$forceUpdate();
            }, 100);

        },
        saveLine(index){
            axios.post('/' + this.document.company.name + '/' + this.type + '/' + this.document.id + '/saveLine', this.nouvellesEntrées[index]).then(response => {
                console.log(response.data);
                this.$set(this.entrees, this.entrees.length, this.nouvellesEntrées[index])
                this.removeNewLine(index)
                this.$forceUpdate();
            }).catch(error => {
                this.displayErrorModal("Une erreur est survenue lors de la sauvegarde de la nouvelle entrée. S'il vous plaît, veuillez réessayer!", (error.message + '' + error.response.data.message) );
            });
        },
        deleteSelected(){
            axios.post('/' + this.document.company.name + '/' + this.type + '/' + this.document.id + '/deleteSelectedItems', this.itemsChecked).then(response => {
                this.displayModal('Les éléments sélectionnés ont été supprimés avec succès')
            }).catch(error => {
                this.displayErrorModal("Une erreur est survenue lors de la sauvegarde de la nouvelle entrée. S'il vous plaît, veuillez réessayer!", (error.message + '' + error.response.data.message) );
            });
        },
        updateSelected(){
            axios.post('/' + this.document.company.name + '/' + this.type + '/' + this.document.id + '/updateSelectedItems', this.document.entrees).then(response => {
                this.document.entrees.forEach(element => {
                    element.edit = false
                })
                this.editMode = false;
                this.$forceUpdate();
            }).catch(error => {
                this.displayErrorModal("Une erreur est survenue lors de la sauvegarde de la nouvelle entrée. S'il vous plaît, veuillez réessayer!", (error.message + '' + error.response.data.message) );
            });
        },
        setEntreeEditToFalse(){
            this.document.entrees.forEach((element, index) => {
                element.edit = false
            })
        },
        setElementsCheckedToFalse(){
            this.document.entrees.forEach((element, index) => {
                this.itemsChecked[index] = false;
            })
        },
        editSelected(){
            this.document.entrees.forEach((element, index) => {
                if(this.itemsChecked[index] === true){
                    element.edit = true
                    Vue.set(this.document.entrees, index, element)
                    this.editMode = true;
                } 
            });
            if(this.editMode === false){
                this.infoEditMode = true
            }
            // if(this.editMode === false ){
            //     $('#editButton').popover({
            //         content: '<button class="btn btn-primary">Modifier Client</button><br><button class="btn btn-primary">Modifier Objet</button><br>',
            //         html: true
            //     })
            // }

            this.$forceUpdate();
        },
        // Fonction pour sauvegarder les Informations liés au document (Vendeur,Objet, ...)
        saveInfo(){
            if(this.document.client_id !== null){
                this.infos.client_id = this.document.client_id;
            }
            axios.post('/' + this.document.company.name + '/' + this.type + '/' + this.document.id + '/saveInfo', this.infos).then(response => {
                this.document.objet = this.infos.objet;
                this.document.date = this.infos.date;
                this.document.client_id = this.infos.client_id;
                this.document.échéance = this.infos.échéance;
                this.clients.forEach(client => {
                    if(client.id === this.document.client_id){
                        this.document.client = client
                    }
                })
                this.infoEditMode = false;
                this.$forceUpdate();
            }).catch(error => {
                this.displayErrorModal("Une erreur est survenue lors de la sauvegarde de la nouvelle entrée. S'il vous plaît, veuillez réessayer!", (error.message + '' + error.response.data.message) );
            });
        },
        wn(number){
            return this.toUpper(writtenNumber(number))
        },
        toUpper(str) {
            return str
                .toLowerCase()
                .split(' ')
                .map(function(word) {
                    return word[0].toUpperCase() + word.substr(1);
                })
                .join(' ')
                .split('-')
                .map(function(word) {
                    return word[0].toUpperCase() + word.substr(1);
                })
                .join('-');
        },
        addPayment(){
            axios.post('/' + this.document.company.name + '/Payement/' + this.document.id + '/addPayment', this.payment ).then(response => {
                if(response.data === 'Erreur'){
                    $('#payementModal').modal('hide');
                    this.displayErrorModal("Le montant rentré est supérieur au montant de la facture.", "")
                    setTimeout(() => {
                        $('#errorModal').modal('hide')
                        $('#payementModal').modal('show');
                    }, 3000);
                } else {
                    $('#payementModal').modal('hide');
                    this.displayModal('Le Payement a été enregistré avec succès')
                }
                
            }).catch(error => {
                console.log(error);
            });
        },
        ajouterARejetter(document){
            this.message = "Voulez-vous vraiment rejetter " + this.type + " Nº" + document.numéro + ". Cette action est irréversible";
            this.aModifier.document = document
            this.aModifier.etat = "Rejetté"
            this.$forceUpdate()
            $('#confirmDeleteModal').modal('show')

        },
        ajouterAValider(document){
            this.message = "Voulez-vous vraiment valider " + this.type + " Nº" + document.numéro + ". Cette action est irréversible";
            this.aModifier.document = document
            if (this.type === 'Facture')
                this.aModifier.etat = "E.A.P"
            else
                this.aModifier.etat = "Validé"
            this.$forceUpdate()
            $('#confirmDeleteModal').modal('show')

        },
        ajouterAEAV(document){
            this.message = "Voulez-vous vraiment enregistrer la " + this.type + " Nº" + document.numéro + ". Une demande de validation sera envoyée si nécessaire.";
            this.aModifier.document = document
            this.aModifier.etat = "E.A.V"
            this.$forceUpdate()
            $('#confirmDeleteModal').modal('show')
        },
        rejetter(){
            axios.post('/' + this.document.company.name + '/' + this.type + '/rejetter', this.aModifier).then(response => {
                this.displayModal(this.type === 'Facture' ? 'La ' : 'Le ' + this.type + ' Nº' + this.aModifier.document.numéro +  ' a été validé avec succès')
                this.aModifier.document = null;
                this.aModifier.etat = null;
            }).catch(error => {
                this.displayErrorModal("Une erreur est survenue lors du rejet de la nouvelle entrée. S'il vous plaît, veuillez réessayer!", (error.message + '' + error.response.data.message) );
            });
        },   
        valider(){
            axios.post('/' + this.document.company.name + '/' + this.type + '/rejetter', this.aModifier).then(response => {
                if(this.aModifier.etat === 'E.A.V'){
                    this.displayModal( (this.type === 'Facture' ? 'La ' : 'Le ') + this.type + ' Nº ' + this.aModifier.document.numéro +  ' a été enregistré avec succès');
                } else {
                    this.displayModal( (this.type === 'Facture' ? 'La ' : 'Le ') + this.type + ' Nº ' + this.aModifier.document.numéro +  ' a été validé avec succès');
                }  
                this.aModifier.document = null;
                this.aModifier.etat = null;
            }).catch(error => {
                this.displayErrorModal("Une erreur est survenue lors de la validation du document. S'il vous plaît, veuillez réessayer!", (error.message + '' + error.response.data.message) );
            });
        },
        // Transformer un devis en facture
        devisToFacture(){
            this.newFacture.client = this.document.client_id;
            this.newFacture.numero = this.newNumber.replace('D', 'F');
            this.newFacture.entrees = this.document.entrees;
            
            axios.post('/' + this.document.company.name + '/Facture/store', {company: this.document.company_id, document: this.newFacture, infos: this.infos}).then(response => {
                $('#newDocument').modal('hide');
                // console.log(response.data.id)
                window.location.href = '/' + this.document.company.name + '/Facture/' + response.data.id;
            }).catch(error => {
                console.log(error);
            });
        },
        // Print Document
        printDocument(){
            this.printing = true;
            $('[data-toggle="tooltip"]').tooltip('hide')
            setTimeout(() => {
                
                // document.getElementById('buttons').style.visibility = 'hidden'
                var x = document.getElementsByClassName('checkButtons')
                for (var i = 0; i < x.length; i++) {
                  x[i].style.visibility = 'hidden';
                }
                window.print()
                // document.getElementById('buttons').style.visibility = 'visible'
                this.printing = false;
            }, 700);
            
        },
    }, 
    mounted(){
        this.entrees = this.document.entrees
        this.infos.date = this.document.date
        this.infos.vendeur = this.document.créateur
        this.infos.objet = this.document.objet
    },
    created(){
        this.payment.facture_id = this.document.id
        this.setEntreeEditToFalse();
        this.setElementsCheckedToFalse();
        this.company.services.forEach(service => {
            service.ref = service.référence + ' ( ' + service.désignation + ' )'
        })
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
.no-border{
      border: none!important;
}
.table td.fit, 
.table th.fit {
    white-space: nowrap;
    width: 1%;
}

.rejetté {  
  display: block;
  position: relative;
  height:100%;
  width:100%;
}

.rejetté::after {
    content: "";
    background: url(/img/rejetté.png) no-repeat;
    opacity: 0.2;
    top: 20%;
    left: 25%;
    bottom: 0;
    right: 0;
    position: absolute;
    z-index: 1;
    width: 50%;
    height: 23%;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>