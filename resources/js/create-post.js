const axios = require('axios');

window.Vue = require('vue');
import wysiwyg from "vue-wysiwyg";

export const serverBus = new Vue();

Vue.use(wysiwyg, {});
Vue.component('create-post', require( './components/CreatePost.vue' ).default)


const posts_wrapper = new Vue({
    el: '#create-post',
});

