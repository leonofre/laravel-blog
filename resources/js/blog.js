const axios = require('axios');

window.Vue = require('vue');


Vue.component('posts-loop', require( './components/Posts.vue' ).default)

const posts_wrapper = new Vue({
    el: '#posts-wrapper',
});
