<template>
	<div>
		<div class="card-header">
			<a :href="create_post_url"><i class="fas fa-plus"></i> Novo Post</a>
			<h2>Meus Posts</h2>
			<form @submit="getPosts">
				<input type="text" name="search" placeholder="Buscar por" v-model="search">
				<button><i class="fas fa-search"></i></button>
			</form>
		</div>

        <posts-loop class="card-body" id="user-posts">
        </posts-loop>
	</div>
</template>
<script>
	import { serverBus } from '../blog';
	const user_token = USER_TOKEN;
	export default {
		data () {
			return {
				posts: [{}],
				is_loading: true,
				has_posts: false,
				page: '' === window.location.search ? 1 : parseInt( window.location.search.split( '=' ).pop() ),
				more_pages: false,
				pages: [],
				total: 0,
				search: '' === window.location.search ? '' : window.location.search.split( '=' ).pop(),
				home_url: HOME_URL,
				create_post_url: HOME_URL + '/post',
				posts_number: 1,
				default_image: APP_URL + '/images/default_image.png'
			}
		},
		methods: {
			getPosts: function( event ) {
				event.preventDefault();

				if ( this.search ) {
					var data = {
						search : this.search,
					};

					serverBus.$emit( 'user-posts-filter', data );
					return true;
				}

				this.errors = [];

				this.errors.push( 'Preencha o campo de busca.' );
			}
		}
	}
</script>
