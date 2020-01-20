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
                    <small>{{ post.author }} - {{ post.created_at }}</small>
                </div>
            </a>
        </article>
        <article v-else>
            <p>Não foram encontrados posts.</p>
        </article>
    </div>
    <div v-else>
        <article v-for="post in posts" v-bind:key="post.id" class="posts">
            <div class="pre-load">
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
            </div>
        </article>
    </div>
</template>
<script>
    import { serverBus } from '../blog';
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
                page: '' === window.location.search ? 1 : parseInt( window.location.search.split( '=' )[1].split( '&' )[0] ),
                search: '' === window.location.search ? '' : window.location.search.split( '=' )[2].split( '&' )[0],
                author: '' === window.location.search ? '' : window.location.search.split( '=' ).pop(),
                more_pages: false,
                pages: [],
                total: 0,
                posts_number: 12,
                default_image: APP_URL + '/images/default_image.png'
            }
        },
        mounted () {
            this.searchPosts();
        },
        created() {
            serverBus.$on( 'posts-filter', ( data ) => {
                this.is_loaded = false;
                this.search = data.title;
                this.author = data.author;
                window.location.href = BLOG_URL + `?page=1&search=${this.search}&author=${this.author}`
            });
        },
        methods: {
            searchPosts: function() {
                axios
                .post( API_URL + `posts/${this.posts_number}/${this.page}`, {
                    search: this.search,
                    author: this.author
                })
                .then( response => {
                    this.is_loaded = true;
                    this.total     = response.data.total;
                    this.per_page  = response.data.per_page;

                    if ( 200 === response.status ) {
                        response.data.data.map( ( post, index ) => {
                            response.data.data[ index ].url = BLOG_URL + `/${post.slug}`;
                        });

                        this.posts     = response.data.data;
                        this.has_posts = true;
                    } else {
                        this.posts     = [{}];
                        this.has_posts = false;
                    }
                    this.paginationLinks();
                })
            },
            paginationLinks: function() {
                this.pages = [];
                    console.log( this.per_page );
                if ( this.total > this.per_page ) {
                    this.more_pages = true;
                    var pages       = Math.ceil( this.total / this.per_page );
                    for ( var i = 1; i <= pages; i++ ) {
                        this.pages.push({
                            link: BLOG_URL + `?page=${i}&search=${this.search}&author=${this.author}`,
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
