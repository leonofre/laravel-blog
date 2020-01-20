const axios = require('axios');

window.Vue = require('vue');

export const serverBus = new Vue();


Vue.component('posts-loop', require( './components/UserPosts.vue' ).default)


const posts_wrapper = new Vue({
    el: '#user-posts',
});

