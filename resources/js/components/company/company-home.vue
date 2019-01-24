<template>
    <div>
        <div class="container-fluid ">
            <div class="row jumbotron black-gradient box-shadow">
                <div class="col-md-12">
                    <h1 class="text-center text-white">Système de Facturation {{ company.name }} </h1>
                </div>
            </div>
        </div>
        <div class='container-fluid'>
            <div class="row mt-7 px-7">
                <a :href="'/' + company.name + '/' + 'Devis'" class="text-shadow box-shadow col border rounded text-center py-7 px-3 mx-1 bg-warning">
                    <div>
                        <i class="fas fa-file-invoice fa-3x text-light"></i>
                        <h3 class="mt-3 text-white">Devis</h3>
                    </div>
                </a>
                <a :href="'/' + company.name + '/' + 'Facture'" class=" box-shadow col border rounded text-center py-7 px-3 mx-1 bg-info">
                    <div>
                        <i class="fas fa-file-invoice-dollar fa-3x text-light"></i>
                        <h3 class="mt-3 text-white">Factures</h3>
                    </div>
                </a>
                <a :href="'/' + company.name + '/' + 'Payement'" class=" box-shadow col border rounded text-center py-7 px-3 mx-1 bg-danger" v-if="manager">
                    <div>
                        <i class="fas fa-calculator fa-3x text-light"></i>
                        <h3 class="mt-3 text-white">Caisse</h3>
                    </div>
                </a>
                <a :href="'/' + company.name + '/' + 'Client'" class=" box-shadow col border rounded text-center py-7 px-3 mx-1 bg-success">
                    <div>
                        <i class="fas fa-users fa-3x text-light"></i>
                        <h3 class="mt-3 text-white">Clients</h3>
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
    background: -moz-linear-gradient(left, rgba(181,189,200,1) 0%, rgba(130,140,149,1) 40%, rgba(130,140,149,1) 50%, rgba(130,140,149,1) 56%, rgba(130,140,149,1) 62%, rgba(181,189,200,1) 100%); /* FF3.6-15 */
    background: -webkit-linear-gradient(left, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 40%,rgba(130,140,149,1) 50%,rgba(130,140,149,1) 56%,rgba(130,140,149,1) 62%,rgba(181,189,200,1) 100%); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(to right, rgba(181,189,200,1) 0%,rgba(130,140,149,1) 40%,rgba(130,140,149,1) 50%,rgba(130,140,149,1) 56%,rgba(130,140,149,1) 62%,rgba(181,189,200,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b5bdc8', endColorstr='#b5bdc8',GradientType=1 ); /* IE6-9 */
}
</style>
