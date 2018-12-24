require('./bootstrap');

window.Vue = require('vue');



const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

Vue.component('company-home', require('./components/company/company-home.vue'));
Vue.component('index-document', require('./components/index-document.vue'));
Vue.component('index-client', require('./components/index-client.vue'));

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
    mounted(){
        
        $(function () {
          $('[data-toggle="popover"]').popover()
        })
    }
});
global.wn = writtenNumber
global.vm = app;