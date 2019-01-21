<template>
    <div class='container'>
        <div class="jumbotron text-center">
            <p class="lead">Cash Balance</p>
            <h1 class="display-3">FCFA {{ totalCaisse | currency}}</h1>
            <hr class="my-2">
            <p class="lead mt-5">
                <button class="btn btn-success btn-lg" role="button" data-toggle="modal" data-target="#recevoirPayement">Reçevoir Payement</button>
                <button class="btn btn-danger btn-lg" role="button" data-toggle="modal" data-target="#retirerEspece">Retirer Espèce</button>
                <button class="btn btn-dark btn-lg" role="button" data-toggle="modal" data-target="#fermerCaisse">Fermer Caisse</button>
            </p>
        </div>

        <div>
            <p>Cash Activity</p>
            <div class="m-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Note</th>
                            <th>Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="transaction in transactions">
                            <td scope="row">{{ transaction.created_at }}</td>
                            <td>{{ transaction.type }}</td>
                            <td>{{ transaction.note }}</td>
                            <td>{{ transaction.montant | currency }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="retirerEspece" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="form-group">
                              <label for="">Montant</label>
                              <input type="text" class="form-control" v-model="retrait.montant" aria-describedby="helpId" placeholder="">
                            </div>
                            <div class="form-group">
                              <label for="">Note</label>
                              <textarea class="form-control" v-model="retrait.note" id="" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="retirerArgent()">Retirer Argent</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="fermerCaisse" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Fermer Caisse</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row my-3">
                                <div class="col">
                                    <h6 class="display-5">Récapitulatif Transactions</h6>
                                    <hr class="my-2">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Montant Attendu (FCFA)</th>
                                                <th>Montant Compté (FCFA)</th>
                                                <th>Différence (FCFA)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">Espèces en Caisse</td>
                                                <td class="text-right">
                                                    {{ totalCaisse }}
                                                </td>
                                                <td>
                                                    <input class="form-control form-control-sm" type="number" v-model="fermeture.compté">
                                                </td>
                                                <td
                                                    :class="this.differenceCaisse < 0 ? 'text-danger' : 'text-success' "
                                                >
                                                    {{ differenceCaisse }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="fermerCaisse()">Fermer Caisse</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Payement Modal  -->
        <div class="modal fade" id="recevoirPayement" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Effectuer Payement</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <p class="text-success text-center" v-if="successMessage">{{ successMessage }}</p>
                                <p class="text-danger text-center" v-if="errorMessage">{{ errorMessage }}</p>
                            </div>
                            
                        </div>
                        <div class="row flex justify-content-center mb-3">
                            <div class="form-group col-9">
                                <label for=""></label>
                                <select class="form-control" @change="chercheFacture(payment.facture_id)" v-model="payment.facture_id">
                                    <option 
                                        v-for="facture in company.factures" 
                                        v-if="facture.etat === 'Validé' || facture.etat === 'Paiement Partiel' "
                                        :value="facture.id" 
                                    >
                                        {{ facture.numéro }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row flex justify-content-between px-3">
                            <p><strong>Client:</strong></p>
                            <p><strong>Montant Facture:</strong></p>
                        </div>
                        <div class="row flex justify-content-between px-3 mt-0" >
                            <p>{{facture ? (facture.client.nom + ' ' + facture.client.prénom) : 'N/A' }}</strong></p>
                            <p v-if="facture">{{ grandTotal | currency }}</p>
                            <p v-else>N/A</p>
                        </div>
                         
                        <div class="row flex justify-content-between px-3">
                            <p>
                                <strong v-if="facture">{{ this.facture.payements.length }} {{ this.facture.payements.length > 1 ? 'Payements Reçus' : 'Payement Reçu'}} </strong>
                                <strong v-else>0 Payement Reçu</strong>
                            </p>

                            <p><strong>Reste à Payer</strong></p>
                        </div>
                        
                        <div class="row flex justify-content-between px-3 mt-0">
                            <p>
                                <strong v-if="facture">{{ totalPayements | currency }}</strong>
                                <strong v-else>N/A</strong>
                            </p>
                            <p class="text-danger"><strong>{{ grandTotal - totalPayements - payment.montant | currency }}</strong></p>
                            <!--  -->
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
                                <textarea type="text" class="form-control" v-model="payment.note" rows=3>
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger"  @click="addPayment()">Ajouter Paiement</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['company', 'caisse'],
    data(){
        return {
            transactions : [],
            retraitsEffectués: [],
            fermeture: {
                attendu: null,
                compté: null,
                difference: null,
            },
            retrait : {
                montant : null,
                note: null
            },
            payment : {
                facture_id : null,
                montant : null,
                note: null
            },
            successMessage: null,
            errorMessage : null,
            facture : null
        }
    },
    computed : {
        totalCaisse(){
            var total = 0;
            this.transactions.forEach( transaction => {
                if(transaction.type === 'Payement'){
                    total += transaction.montant
                } else {
                    total -= transaction.montant
                }
            })
            return total;
        },
        differenceCaisse(){
            if(this.fermeture.compté){
                return this.fermeture.compté - this.totalCaisse
            }
        },
        subtotal(){
            let sub = 0;
            if(this.facture){
                this.facture.entrees.forEach(element => {
                    sub += (element.quantité * element.prix_unitaire)
                })
                return sub;
            }
            
        },
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
            if(this.facture){
                let total = 0;
                this.facture.payements.forEach(payement => {
                    total += payement.montant
                })
                return total;
            } 
        },
    },
    methods:{
        getPayements(){
            this.company.factures.forEach(facture => {
                facture.payements.forEach(payement => {
                    if(Date.parse(payement.created_at) > Date.parse(this.company.derniere_fermeture_caisse)){
                        payement.type = 'Payement'
                        this.transactions.push(payement)
                    }
                    
                })
            });
            this.company.retraits.forEach( retrait => {
                if(Date.parse(retrait.created_at) > Date.parse(this.company.derniere_fermeture_caisse)){
                    retrait.type = 'Retrait'
                    this.transactions.push(retrait)
                }
                
            })
        },
        retirerArgent(){
            axios.post('/' + this.company.name + '/Payement/retirer-cash', this.retrait ).then(response => {
                location.reload()
                
            }).catch(error => {
                console.log(error);
            });
        },
        fermerCaisse(){
            axios.post('/' + this.company.name + '/fermer-caisse', ).then(response => {
                console.log(response.data);
                location.reload()
            }).catch(error => {
                console.log(error);
            });
        },
        chercheFacture(id){
            axios.get('/' + this.company.name + '/Facture/' + id + '/api').then(response => {
                this.facture = response.data;
            }).catch(error => {
            });
        },
        addPayment(){
            axios.post('/' + this.company.name + '/Payement/' + this.payment.facture_id + '/addPayment', this.payment ).then(response => {
                if(response.data === 'Erreur'){
                    $('#payementModal').modal('hide');
                    this.displayErrorModal("Le montant rentré est supérieur au montant de la facture.", "")
                    setTimeout(() => {
                        $('#errorModal').modal('hide')
                        $('#payementModal').modal('show');
                    }, 3000);
                } else {
                    this.successMessage = 'Le Payement a été enregistré avec succès'; 
                    setTimeout(() => {
                       $('#recevoirPayement').modal('hide');
                    }, 3000);
                    
                    // 
                    // this.displayModal('Le Payement a été enregistré avec succès')
                }

            }).catch(error => {
                console.log(error);
            });
        },
    },
    mounted(){
        this.getPayements()
        this.transactions.sort((a,b) => {
            return Date.parse(a.created_at) - Date.parse(b.created_at)
        })
        this.transactions.reverse();

    }
}
</script>
<style>
.no-border {
    border: 0;
    box-shadow: none; /* You may want to include this as bootstrap applies these styles too */
}
</style>
