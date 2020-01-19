<template>
    <div v-if="is_loaded">
        <article v-for="post in posts" v-bind:key="post.id" class="post">
            <a :href="post.url">
                <img :src="post.image" alt="">
                <div class="content">
                    <h2>{{ post.title }}</h2>
                    <p>{{ post.excerpt }}</p>
                    <small>{{ post.author }}</small>
                </div>
            </a>
        </article>
    </div>
    <div v-else>
        <article v-for="post in posts" v-bind:key="post.id" class="post">
            <div class="loader">
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            </div>
            <img :src="default_image" alt="">
            <div class="content">
                <h2>Post Title</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur, similique fugit quod commodi, deserunt.</p>
                <small>Post Author</small>
            </div>
        </article>
    </div>
</template>
<script>
    const api_url = APP_URL + '/api/';
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
                default_image: APP_URL + '/images/default_image.png'
            }
        },
        mounted () {
            axios
              .get( api_url + 'posts/12/1' )
              .then( response => {
                this.is_loaded = true
                response.data.data.map( ( post, index ) => {
                    response.data.data[ index ].url = APP_URL + '/blog/' + post.slug;
                });

                this.posts = response.data.data
            })
        }
    }
</script>
