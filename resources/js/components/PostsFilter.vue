<template>
	<div>
		<form @submit="this.checkForm" class="form-filter">
				
			<div>
				<input id="title" v-model="title" type="text" name="title" placeholder="Buscar Por">
			</div>

			<div>
				<div class="select-wrapper">
					<select v-model="author" id="author" name="author">
						<option value="" selected="selected">Selecione o autor</option>
				    	<option v-for="author in authors" :value="author.id">{{ author.name }}</option>
				    </select>
				    <i class="fas fa-sort-down"></i>
				</div>
			</div>

			<div>
				<input type="submit" value="Enviar">
			</div>

			<p v-for="error in errors" class="error-wrapper">{{ error }}</p>
		</form>
	</div>
</template>
<script>
	import { serverBus } from '../blog';
	export default {
		data () {
            return {
                errors: [],
                title: null,
                authors: [],
                author: null,
                is_loaded: false,
                title: '' === window.location.search ? '' : window.location.search.split( '=' )[2].split( '&' )[0],
                author: '' === window.location.search ? '' : window.location.search.split( '=' ).pop(),
                default_image: APP_URL + '/images/default_image.png'
            }
        },
        mounted () {
            this.getAuthors();
        },
		methods:{
			checkForm: function ( event ) {
				event.stopPropagation();
				event.preventDefault();

				if ( this.title || this.author ) {
					var data = {
						title : this.title,
						author: this.author
					};

					serverBus.$emit( 'posts-filter', data );
					return true;
				}

				this.errors = [];

				this.errors.push( 'Pelo menos um dos campos deve ser utilizado.' );
			},
			getAuthors: function() {
				axios
                .get( API_URL + `authors` )
                .then( response => {
                    this.is_loaded = true

                    this.authors = response.data
                })
			}
		}
	}
</script>
