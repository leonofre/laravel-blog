const axios = require('axios');

window.Vue = require('vue');

export const serverBus = new Vue();


Vue.component('navigation-links', require( './components/NavigationLinks.vue' ).default)


const posts_wrapper = new Vue({
    el: '#navigation-links',
});

