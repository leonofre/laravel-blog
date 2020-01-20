<template>
    <div>
        <form v-bind:key="post.id" @submit="this.updatePost" class="post">
            <div class="card-header">{{ post.title }}</div>
            <div v-if="is_loading" class="loader">
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            </div>
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
                    <span v-if="message">{{ message }}</span>
            </div>
        </form>
    </div>
</template>
<script>
    import { serverBus } from '../blog';
    const user_token = USER_TOKEN;
    export default {
        data () {
            return {
                post: {},
                is_loading: true,
                title: null,
                errors: [],
                image_name: '',
                image_size: 0,
                id: window.location.pathname.split( '/' ).pop(),
                default_image: APP_URL + '/images/default_image.png',
                message: false,
            }
        },
        mounted () {
            this.getPost();
        },
        methods: {
            getPost: function() {
                axios
                .get( API_URL + `user/post/${this.id}?api_token=${user_token}`)
                .then( response => {
                    
                    this.post = response.data;

                    if ( 200 === response.status ) {
                        this.is_loading = false
                    } else {
                        this.posts     = [{}];
                        this.has_posts = false;
                        window.location.href = APP_URL + '/404';
                    }
                })
            },
            uploadImage: function( event ) {
                var files = event.target.files || event.dataTransfer.files;

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
                .post( API_URL + `user/post/${this.post.id}`, {
                    post: this.post,
                    api_token: user_token,
                    image_name: this.image_name,
                    image_size: this.image_size
                })
                .then( response => {
                    this.is_loading = false

                    this.posts = response.data;

                    if ( 200 === response.status ) {
                        this.message     = 'Post atualizado com sucesso.';
                    } else {
                        this.message     = 'Erro ao atualizar o post, tente novamente.';
                    }
                })
            }
        }
    }
</script>
