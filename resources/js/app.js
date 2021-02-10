
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import moment from 'moment';
import { Form, HasError, AlertError } from 'vform'


import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);



//Sweet alet2
import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

window.toast = toast;


window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.component('pagination', require('laravel-vue-pagination'));

import VueRouter from 'vue-router'
Vue.use(VueRouter)

/*QR IMPORT GLOBAL*/
//import Vue from "vue";
import VueQrcodeReader from "vue-qrcode-reader";
Vue.use(VueQrcodeReader);
/*HASTA AQUI*/
//const Instascan = require('instascan');
//window.Instascan = Instascan;
/*otro qr mejor*/

// configuracion de vue progress bar
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
})

let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },

    { path: '/personal', component: require('./components/Personal.vue').default },
    { path: '/cargos', component: require('./components/Cargos.vue').default },
    { path: '/horario', component: require('./components/Horario.vue').default },
    { path: '/scan', component: require('./components/Scanner.vue').default },
    { path: '/registros', component: require('./components/Registros.vue').default },
    { path: '/reportes', component: require('./components/Reporte.vue').default },

    { path: '*', component: require('./components/NotFound.vue').default }
  ]


const router = new VueRouter({
	mode: 'history',
	routes
})


Vue.filter('upText', function(text){
	//return text.toUpperCase();
	return text.charAt(0).toUpperCase() + text.slice(1);

});


Vue.filter('myDate', function(created){
	return moment(created).locale("es").format('MMMM Do YYYY');
});

Vue.filter('myDiff', function(start, end){
  var uno = moment(start).locale("es");
  var dos = moment(end).locale("es");
  return dos.diff(uno,'H:mm:ss');
});


window.Fire = new Vue();


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Passport
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data: {
        search:''
    },
    methods:{
      searchit: _.debounce(()=> {
          Fire.$emit('searching');
      },1000),

      printme() {
        window.print();
      }
    }
});
