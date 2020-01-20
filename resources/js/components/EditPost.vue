<template>
    <div v-if="is_loaded">
        <div class="card-header">{{ post.title }}</div>
        <form v-bind:key="post.id" @submit="this.updatePost" class="post">
            <div class="form-row">
                    <label for="title">
                        <legend>Título</legend>
                        <input id="title" v-model="post.title" type="text" name="title">
                    </label>
                    <label for="description">
                        <legend>Descrição</legend>
                        <wysiwyg id="description" v-model="post.description" name="description" />
                    </label>
            </div>
            <div class="form-row">
                    <label for="image">
                        <legend>Imagem</legend>
                        <input id="image" v-on:change="uploadImage" type="file" name="image">
                    </label>
                    <img :src="post.image" alt="">
            </div>
            <div class="form-row">
                    <input type="submit" value="Enviar">
                    <span v-for="error in errors">{{ error }}</span>
            </div>
        </form>
    </div>
    <div v-else>
        <div class="loader">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        </div>
    </div>
</template>
<script>
    import { serverBus } from '../blog';
    const api_url    = APP_URL + '/api/';
    const blog_url   = APP_URL + '/blog';
    const user_token = USER_TOKEN;
    export default {
        data () {
            return {
                post: {},
                is_loaded: false,
                title: null,
                errors: [],
                image_name: '',
                image_size: 0,
                id: window.location.pathname.split( '/' ).pop(),
                default_image: APP_URL + '/images/default_image.png'
            }
        },
        mounted () {
            this.getPost();
        },
        methods: {
            getPost: function() {
                axios
                .get( api_url + `user/post/${this.id}?api_token=${user_token}`)
                .then( response => {
                    
                    this.post = response.data;

                    if ( 200 === response.status ) {
                        this.is_loaded = true
                    } else {
                        this.posts     = [{}];
                        this.has_posts = false;
                        window.location.href = APP_URL + '/404';
                    }
                })
            },
            uploadImage: function( event ) {
                var files = event.target.files || event.dataTransfer.files;

                console.log( files );
                if ( ! files.length ) {
                    return;
                }

                this.createImage( files[0] );
            },
            createImage: function( file ) {
                this.image_name = file.name;
                this.image_size = file.size;
                let reader = new FileReader();
                reader.onload = ( event ) => {
                    this.post.image = event.target.result;
                };
                reader.readAsDataURL( file );

            },
            updatePost: function( event ) {
                event.preventDefault();

                axios
                .post( api_url + `user/post/${this.post.id}`, {
                    post: this.post,
                    api_token: user_token,
                    file_name: this.image_name,
                    file_size: this.image_size
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
            }
        }
    }
</script>
