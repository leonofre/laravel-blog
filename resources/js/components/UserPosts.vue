<template>
	<table class="table">
		<div v-if="is_loading" class="loader">
			<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
		</div>
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Título</th>
				<th scope="col">Resumo</th>
				<th scope="col" colspan=2>Ações</th>
			</tr>
		</thead>
		<tbody>
			<tr v-if="has_posts" v-for="post in posts" v-bind:key="post.id" class="posts">
				<td>{{ post.id }}</td>
				<td>{{ post.title }}</td>
				<td>{{ post.excerpt }}</td>
				<td><a :href="post.url">Editar</a></td>
				<td><button>Deletar</button></td>
			</tr>
			<tr v-else>
				<td colspan="5">Não foram encontrados posts.</td>
			</tr>
		</tbody>
	</table>
</template>
<script>
	import { serverBus } from '../blog';
	const api_url    = APP_URL + '/api/';
	const blog_url   = APP_URL + '/blog';
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
				posts_number: 1,
				default_image: APP_URL + '/images/default_image.png'
			}
		},
		mounted () {
			this.getPosts();
		},
		methods: {
			getPosts: function() {
				axios
				.get( api_url + `user/posts/${this.posts_number}/${this.page}?api_token=${user_token}`)
				.then( response => {
					this.is_loading = false

					if ( 200 === response.status ) {
						response.data.data.map( ( post, index ) => {
							response.data.data[ index ].url = APP_URL + '/home/post/' + post.id;
						});

						this.posts = response.data.data;
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
