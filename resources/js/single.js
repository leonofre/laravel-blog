const axios = require('axios');

window.Vue = require('vue');

export const serverBus = new Vue();


Vue.component('post-data', require( './components/Single.vue' ).default)


const posts_wrapper = new Vue({
    el: '#post-data',
});

