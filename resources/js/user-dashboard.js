const axios = require('axios');

window.Vue = require('vue');

export const serverBus = new Vue();


Vue.component('user-dashboard', require( './components/UserDashboard.vue' ).default)


const posts_wrapper = new Vue({
    el: '#user-dashboard',
});

