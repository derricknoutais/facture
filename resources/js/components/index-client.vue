

<script>
export default {
    props: ['clients', 'company'],
    data(){
        return {
            newClient: {
                nom: '',
                prenom: '',
                addresse: '',
                numéro: ''
            },
            clientUpdate: {
                nom: '',
                prenom: '',
                addresse: '',
                numéro: ''
            },
            newCompany : {
            },
            isLoading: false,
            clientLocal: null
        }
    },
    methods:{
        clearClient(){
            this.newClient.nom = ''
            this.newClient.prenom = ''
            this.newClient.addresse = ''
            this.newClient.numéro = ''
        },
        créerClient(){
            axios.post('/' + this.company.name + '/Client/store', this.newClient ).then( response => {
                console.log(this.newClient)
                console.log(response.data)
                
                this.newClient.prénom = this.newClient.prenom;
                this.clientLocal.push(this.newClient)
                // this.$alertify.confirm(
                //   'confirm title',
                //   'This is comfirm',
                //   () => this.$alertify.success('ok'),
                //   () => this.$alertify.error('cancel')
                // );
                this.$forceUpdate();
                $('#newClient').modal('hide')
            }).catch(error => {
                console.log(error);
            });
        },
        updateClient(index){
            axios.post('/client/' + clientLocal[index].id + '/update', this.clientUpdate).then(response => {
                console.log(response.data);
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