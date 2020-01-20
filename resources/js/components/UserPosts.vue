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
				<td><a :href="post.url"><i class="fas fa-pencil-alt"></i></a></td>
				<td>
					<button v-on:click="deletePost"><i class="far fa-trash-alt"></i></button>
					<div v-if="confirmation">
						<label>Você deseja realmente deletar este post?</label>
						<div class="options">
							<button v-on:click="doDelete" :value="post.id">Sim</button>
							<button v-on:click="dontDelete">Não</button>
						</div>
					</div>
				</td>
			</tr>
			<tr v-else>
				<td colspan="5">Não foram encontrados posts.</td>
			</tr>
		</tbody>
	</table>
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
				page: '' === window.location.search ? 1 : parseInt( window.location.search.split( '=' )[1].split( '&' )[0] ),
				more_pages: false,
				pages: [],
				total: 0,
				posts_number: 1,
				search: '' === window.location.search ? '' : window.location.search.split( '=' ).pop(),
				confirmation: false,
				default_image: APP_URL + '/images/default_image.png'
			}
		},
		mounted () {
			this.searchPosts();
		},
		created() {
            serverBus.$on( 'user-posts-filter', ( data ) => {
                this.is_loaded = false;
                this.search = data.search;
                window.location.href = BLOG_URL + `?page=1&search=${this.search}`;
            });
        },
		methods: {
            searchPosts: function() {
                axios
                .post( API_URL + `user/posts/${this.posts_number}/${this.page}`, {
                    search: this.search,
                    api_token: user_token
                })
                .then( response => {
                    this.is_loading = false;
                    this.total     = response.data.total;
                    this.per_page  = response.data.per_page;
                    console.log( this.total )

                    if ( 200 === response.status ) {
                        response.data.data.map( ( post, index ) => {
                            response.data.data[ index ].url = HOME_URL + `/${post.slug}`;
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
							link: HOME_URL + `?page=${i}&search=${this.search}`,
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
			},
			deletePost: function() {
				this.confirmation = true;
			},
			doDelete: function( event ) {
				axios
                .delete( API_URL + `user/post/${event.target.value}?api_token=${user_token}`)
                .then( response => {
                    if ( 200 === response.status && 1 === response.data ) {
                    	window.location.href = HOME_URL;
                    }
                })
			},
			dontDelete: function() {
				this.confirmation = false;
			}
		}
	}
</script>
