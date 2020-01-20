<template>
    <div v-if="is_loaded">
        <article v-if="has_posts" v-for="post in posts" v-bind:key="post.id" class="posts">
            <a :href="post.url">
                <div class="image-wrapper">
                    <img :src="post.image" alt="">
                </div>
                <div class="content">
                    <h2>{{ post.title }}</h2>
                    <p>{{ post.excerpt }}</p>
                    <small>{{ post.author }}</small>
                </div>
            </a>
        </article>
        <article v-else>
            <p>Não foram encontrados posts.</p>
        </article>
    </div>
    <div v-else>
        <article v-for="post in posts" v-bind:key="post.id" class="posts">
            <div class="loader">
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            </div>
            <div class="image-wrapper">
                <img :src="default_image" alt="">
            </div>
            <div class="content">
                <h2>Post Title</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur, similique fugit quod commodi, deserunt.</p>
                <small>Post Author</small>
            </div>
        </article>
    </div>
</template>
<script>
    import { serverBus } from '../blog';
    const api_url  = APP_URL + '/api/';
    const blog_url = APP_URL + '/blog';
    export default {
        data () {
            return {
                posts: [
                    {},
                    {},
                    {},
                    {}
                ],
                is_loaded: false,
                has_posts: false,
                page: '' === window.location.search ? 1 : parseInt( window.location.search.split( '=' ).pop() ),
                more_pages: false,
                pages: [],
                total: 0,
                posts_number: 12,
                default_image: APP_URL + '/images/default_image.png'
            }
        },
        mounted () {
            this.getPosts();
        },
        created() {
            serverBus.$on( 'posts-filter', ( data ) => {
                this.is_loaded = false;
                this.searchPosts( data.title, data.author );
            });
        },
        methods: {
            getPosts: function() {
                axios
                  .get( api_url + `posts/${this.posts_number}/${this.page}` )
                  .then( response => {
                    this.is_loaded = true
                    this.total    = response.data.total;
                    this.per_page = response.data.per_page;

                    response.data.data.map( ( post, index ) => {
                        response.data.data[ index ].url = APP_URL + '/blog/' + post.slug;
                    });

                    this.posts = response.data.data;

                    if ( 200 === response.status ) {
                        this.has_posts = true;
                        this.paginationLinks();
                    } else {
                        this.posts     = [{}];
                        this.has_posts = false;
                    }
                })
            },
            searchPosts: function( search = '', author = 0 ) {
                axios
                .post( api_url + `posts/${this.posts_number}/${this.page}`, {
                    search: search,
                    author: author
                })
                .then( response => {
                    this.is_loaded = true
                    response.data.data.map( ( post, index ) => {
                        response.data.data[ index ].url = APP_URL + '/blog/' + post.slug;
                    });

                    this.posts = response.data.data;

                    if ( 200 === response.status ) {
                        this.has_posts = true;
                        this.paginationLinks();
                    } else {
                        this.posts     = [{}];
                        this.has_posts = false;
                    }
                })
            },
            paginationLinks: function() {
                if ( this.total > this.posts_number ) {
                    this.more_pages = true;
                    var pages = Math.ceil( this.total / this.per_page );
                    for ( var i = 1; i <= pages; i++ ) {
                        this.pages.push({
                            link: blog_url + `?page=${i}`,
                            number: i,
                            current: i === this.page ? 'current navigation-link' : 'navigation-link'
                        });
                    }
                } else {
                    this.more_pages = false;
                }

                var data = {
                    links: this.pages,
                    more_pages: this.more_pages
                }

                serverBus.$emit( 'more-pages', data );
            }
        }
    }
</script>
