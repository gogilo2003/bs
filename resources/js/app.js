/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue').default;
import Vue from 'vue'
import datePicker from 'vue-bootstrap-datetimepicker';
import VueGoodTablePlugin from 'vue-good-table';

import router from './routes'

import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
import 'vue-good-table/dist/vue-good-table.css'
import '@fortawesome/fontawesome-svg-core/styles.css'

Vue.component('navbar', require('./components/Navbar.vue').default);

Vue.use(VueGoodTablePlugin);
Vue.use(datePicker);

const app = new Vue({
    router,
    el: '#app',
});

