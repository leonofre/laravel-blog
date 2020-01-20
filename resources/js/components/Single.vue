<template>
    <div v-if="is_loaded">
        <article v-if="has_post" v-bind:key="post.id" class="post">
            <div class="single-header">
                <img :src="post.image" alt="">
                <h2>{{ post.title }}</h2>
            </div>
            <div class="content">
                <div v-html="post.description"></div>
                <small>{{ post.author }} - {{ post.created_at }}</small>
            </div>
            <nav class="navigation-wrapper">
                <a class="navigation-link" :href="post.prev_link"><i v-if="post.prev_name" class="fas fa-arrow-circle-left"></i>{{post.prev_name}}</a>
                <a class="navigation-link" :href="post.next_link">{{post.next_name}} <i v-if="post.next_name" class="fas fa-arrow-circle-right"></i></a>
            </nav>
        </article>
    </div>
    <div v-else>
        <article class="post">
            <div class="loader">
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            </div>
            <div class="single-header">
                <img :src="default_image" alt="">
                <h2>Post Title</h2>
            </div>
            <div class="content">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur, similique fugit quod commodi, deserunt.</p>
                <small>Post Author</small>
            </div>
        </article>
    </div>
</template>
<script>
    import { serverBus } from '../blog';
    export default {
        data () {
            return {
                post: {},
                is_loaded: false,
                has_post: false,
                default_image: APP_URL + '/images/default_image.png'
            }
        },
        mounted () {

            var slug = window.location.pathname.split( '/' ).pop();

            this.getPost( slug );
        },
        created() {
            serverBus.$on( 'posts-filter', ( data ) => {
                this.is_loaded = false;
                this.searchPosts( data.title, data.author );
            });
        },
        methods: {
            getPost: function( slug = '' ) {
                axios
                  .get( API_URL + `posts/single/${slug}` )
                  .then( response => {
                    this.is_loaded = true

                    this.post = response.data;

                    if ( 200 === response.status ) {
                        this.has_post = true;
                    } else {
                        this.post     = {};
                        this.has_post = false;
                        window.location.href = APP_URL + '/404';
                    }
                })
            }
        }
    }
</script>
