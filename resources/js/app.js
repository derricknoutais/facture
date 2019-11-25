require('./bootstrap');

window.Vue = require('vue');

import Multiselect from 'vue-multiselect';
import VueAlertify from 'vue-alertify';

Vue.use(VueAlertify, {
    notifier: {
        delay: 2,
        position: 'top-right',
        closeButton: true,
    }
});

Vue.component('multiselect', Multiselect)
const moment = require('moment')
require('moment/locale/fr')

Vue.use(require('vue-moment'), {
    moment
})
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

Vue.component('company-home', require('./components/company/company-home.vue'));
Vue.component('index-document', require('./components/index-document.vue'));
Vue.component('index-client', require('./components/index-client.vue'));
// Vue.component('caisse-index', require('./components/CaisseIndex.vue'));

//Plugins
window.writtenNumber = require('written-number');
import VueCurrencyFilter from 'vue-currency-filter'

Vue.use(VueCurrencyFilter, {
    symbol : '',
    thousandsSeparator: '.',
    fractionCount: 0,
    fractionSeparator: ',',
    symbolPosition: 'front',
    symbolSpacing: true
});
writtenNumber.defaults.lang = 'fr';


const app = new Vue({
    el: '#app',
    data: {
        isLoading: false
    },
    methods: {
        
    },
    mounted(){
        $(function () {
            $('[data-toggle="popover"]').popover()
            $('[data-toggle="tooltip"]').tooltip()
        });
    },
    created(){
        window.Echo.channel('facture').listen('FactureCree', () =>{
            alert('Hello')
            this.$alertify.success("Une factur vient d'être créé")
        });
    }
});

global.wn = writtenNumber
global.vm = app;