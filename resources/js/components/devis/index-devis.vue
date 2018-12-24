<template>
    <div class='container'>
        <div class="row mt-6">
            <div class="col">
                <h1 class="text-center">Répertoire de Devis</h1>
            </div>
        </div>
        <div class="row mt-7">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Numéro de Devis</th>
                            <th>Établi Par</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="devis in devis">
                            <td scope="row"><a :href="'/'+devis.company.name+'/devis/'+ devis.numéro">{{ devis.numéro }}</a></td>
                            <td>{{ devis.créateur.name }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</template>

<script>
export default {
    props: ['devis'],
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