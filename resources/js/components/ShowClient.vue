<template>
    <div class='container'>
        <div class="row">
            <div class="col">
                <h1 class="text-center">Détails Clients</h1>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col">
                <p>{{ clientLocal.nom + ' ' + clientLocal.prénom }}</p>
                <p>{{ clientLocal.numéro }}</p>
                <p>{{ clientLocal.addresse }}</p>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col">
                <h3 class="text-left"><u>Historique des Transactions</u></h3>
            </div>
        </div>
        <div class="row">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Vendeur</th>
                        <th>Type Transaction</th>
                        <th>Numéro Document</th>
                        <th>Montant</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="transaction in transactions">
                        <td>{{ transaction.created_at }}</td>
                        <td>{{ transaction.créateur.name }}</td>
                        <td>{{ transaction.type }}</td>
                        <td>{{ transaction.numéro }}</td>
                        <td>{{ transaction.total | currency }}</td>
                        <td>
                            <a :href="'/'+ company.name+'/' + transaction.type + '/' + transaction.numéro" class="btn btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: ['client', 'company'],
    data(){
        return {
            clientLocal: null,
            transactions: []
        }
    },
    methods:{

    },
    computed:{
        totalTransaction(){
            
        }
    },
    created(){
        this.clientLocal = this.client
        this.client.devis.forEach( devis => {
            devis.type = 'Devis'
            this.transactions.push(devis)
        });
        this.client.factures.forEach( facture => {
            facture.type = 'Facture'
            this.transactions.push(facture)
        });
        this.transactions.sort((a,b) => {
            return Date.parse(a.created_at) - Date.parse(b.created_at);
        })
        this.transactions.reverse();
        this.transactions.forEach(transaction => {
            let total = 0;
            transaction.entrees.forEach(entree => {
                total += (entree.quantité * entree.prix_unitaire)
            })
            transaction.total = total
        })
    }
}
</script>