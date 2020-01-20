const axios = require('axios');

window.Vue = require('vue');
import wysiwyg from "vue-wysiwyg";

export const serverBus = new Vue();

Vue.use(wysiwyg, {});
Vue.component('edit-post', require( './components/EditPost.vue' ).default)


const posts_wrapper = new Vue({
    el: '#edit-post',
});

