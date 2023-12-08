
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.swal = require('sweetalert2');

// require('autonumeric');

// window.datepicker = require('bootstrap-datepicker');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/UserTypeComponent.vue -> <user-type-component></user-type-component>
 */

// Vue.component('user-type-component', require('./components/UserTypeComponent.vue'));

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 import Vue from 'vue';
 import VueRouter from 'vue-router';
 import router from './router';

 try {    
     Vue.use(VueRouter);
    
    const el = $('#app').length ? '#app' : null;
    if(el){
        new Vue({
            el: el,
            data: {},
            mounted() {},
            router
        }).$mount(el);
    }
 } catch (error) { console.log("Vue exception caught") }
