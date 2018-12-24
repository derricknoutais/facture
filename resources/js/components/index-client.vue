<template>
    <div class='container'>
        <div class="row">
            <div class="col">
                <h1 class="text-center">Répertoire Clients</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="client in clients">
                            <td scope="row"> 
                                <a :href="'/' + company.name + '/Client/' + client.id">{{ client.nom + ' ' + client.prénom }}</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['clients', 'company'],
    data(){
        return {

        }
    },
    methods:{

    },
    mounted(){

    },
}
</script>