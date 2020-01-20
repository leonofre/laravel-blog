const axios = require('axios');

window.Vue = require('vue');

export const serverBus = new Vue();


Vue.component('posts-loop', require( './components/Posts.vue' ).default)
Vue.component('posts-filter', require( './components/PostsFilter.vue' ).default)


const posts_wrapper = new Vue({
    el: '#posts-wrapper',
});

const posts_filter = new Vue({
    el: '#posts-filter',
});
