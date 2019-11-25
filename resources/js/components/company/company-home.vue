<template>
    <div>
        <div class="container-fluid ">
            <div class="row jumbotron black-gradient box-shadow">
                <div class="col-md-12">
                    <h1 class="text-center text-dark">Système de Facturation {{ company.name }} </h1>
                </div>
            </div>
        </div>
        <div class='container-fluid'>
            <div class="row tw-mt-10 tw-px-10">
                <a :href="'/' + company.name + '/' + 'Devis'" class="icon-hover text-shadow box-shadow col border rounded text-center tw-py-16 tw-px-5 mx-1 bg-light tw-" v-if="manager || agent" >
                    <div>
                        <i class="fas fa-file-invoice-dollar fa-3x text-dark"></i>
                        <h3 class="mt-3 text-dark tw-text-2xl">Devis</h3>
                    </div>
                </a>
                <a :href="'/' + company.name + '/' + 'Facture'" class="icon-hover box-shadow col border rounded text-center tw-py-16 tw-px-5 mx-1 bg-light" v-if="manager">
                    <div>
                        <i class="fas fa-handshake fa-3x text-dark"></i>
                        <h3 class="mt-3 text-dark tw-text-2xl">Factures</h3>
                    </div>
                </a>
                <a :href="'/' + company.name + '/' + 'Payement'" class="icon-hover box-shadow col border rounded text-center tw-py-16 tw-px-5 mx-1 bg-light" v-if="manager || caissier">
                    <div>
                        <i class="fas fa-calculator fa-3x text-dark"></i>
                        <h3 class="mt-3 text-dark tw-text-2xl">Caisse</h3>
                    </div>
                </a>
                <a :href="'/' + company.name + '/' + 'Client'" class=" box-shadow col border rounded text-center tw-py-16 tw-px-5 mx-1 bg-light hover:tw-bg-red-500">
                    <div>
                        <i class="fas fa-users fa-3x text-dark " ></i>
                        <h3 class="mt-3 text-dark tw-text-2xl">Clients</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['company', 'user'],
    data(){
        return {}
    },
    methods:{},
    computed: {
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
        },
        caissier(){
            if(this.user.role === 'Caissier'){
                return true
            } else {
                return false
            }
        }
    },
    mounted(){

    }
}
</script>
<style>
a { text-decoration: none !important }
.black-gradient {
    background: rgb(181,189,200); /* Old browsers */
    background: -moz-linear-gradient(left, rgba(181,189,200,1) 0%, rgb(205, 210, 214) 40%, rgb(223, 226, 230) 50%, rgb(219, 223, 226) 56%, rgba(130,140,149,1) 62%, rgba(181,189,200,1) 100%); /* FF3.6-15 */
    background: -webkit-linear-gradient(left, rgb(231, 232, 235) 0%, rgb(205, 210, 214) 40%, rgb(190, 194, 199) 50%, rgb(219, 223, 226) 56%, rgb(233, 235, 238) 62%, rgb(231, 232, 235) 0%); /* Chrome10-25,Safari5.1-6 */
    
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#b5bdc8',GradientType=1 ); /* IE6-9 */
}
</style>
