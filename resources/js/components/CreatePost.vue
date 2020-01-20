<template>
	<div>
		<div class="card-header">{{ post.title }}</div>
		<form v-bind:key="post.id" @submit="this.updatePost" class="post">
			<div class="form-row">
				<label for="title">
					<legend>Título</legend>
					<input id="title" v-model="post.title" type="text" name="title">
				</label>
			</div>
			<div class="form-row">
				<label for="description">
					<legend>Descrição</legend>
					<wysiwyg id="description" v-model="post.description" name="description" />
				</label>
			</div>
			<div class="form-row image-row">
				<img :src="post.image" alt="">
				<label for="image">
					<legend>Imagem</legend>
					<input id="image" v-on:change="uploadImage" type="file" name="image">
				</label>
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
				post: {
					image: null,
					title: null,
					description: null,
				},
				errors: [],
				image_name: '',
				image_size: 0,
				id: window.location.pathname.split( '/' ).pop(),
				default_image: APP_URL + '/images/default_image.png',
				message: false,
			}
		},
		methods: {
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
				console.log( this.post.image );
				};
				reader.readAsDataURL( file );

			},
			updatePost: function( event ) {
				event.preventDefault();

				axios
				.post( API_URL + `user/post`, {
					post: this.post,
					api_token: user_token,
					image_name: this.image_name,
					image_size: this.image_size
				})
				.then( response => {
					this.is_loaded = true

					this.posts = response.data;

					if ( 200 === response.status ) {
						window.location.href = HOME_URL + '/post/' + this.posts.id;
					} else {
						this.message     = 'Erro ao criar o post, tente novamente.';
					}
				})
			}
		}
	}
</script>
