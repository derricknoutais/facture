<template>
    <div class='container-fluid'>
        <div class="row mt-6">
            <div class="col-md-12">
                <h1 class="text-center">Système de Facturation {{ company.name }} </h1>
            </div>
        </div>
        <div class="row mt-7 px-7">
            <a :href="'/' + company.name + '/' + 'Devis'" class=" col border rounded text-center py-6 mx-3 bg-primary">
                <div>
                    <img src="/img/icons8-cashflow.png">
                    <h2 class="mt-3 text-white">Devis</h2>
                </div>
            </a>
            <a :href="'/' + company.name + '/' + 'Facture'" class=" col border rounded text-center py-6 mx-3 bg-primary">
                <div>
                    <img src="/img/icons8-receipt.png">
                    <h3 class="mt-3 text-white">Factures</h3>
                </div>
            </a>
            <a :href="'/' + company.name + '/' + 'Payements'" class=" col border rounded text-center py-6 mx-3 bg-primary">
                <div>
                    <img src="/img/icons8-cash_in_hand.png">
                    <h3 class="mt-3 text-white">Payements</h3>
                </div>
            </a>
            <a :href="'/' + company.name + '/' + 'Client'" class=" col border rounded text-center py-6 mx-3 bg-primary">
                <div>
                    <img src="/img/icons8-queue.png">
                    <h3 class="mt-3 text-white">Clients</h3>
                </div>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: ['company'],
    data(){
        return {

        }
    },
    methods:{

    },
    mounted(){

    }
}
</script>
<style>
a { text-decoration: none !important }
</style>
