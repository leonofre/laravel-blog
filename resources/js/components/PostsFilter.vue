<template>
	<div>
		<form @submit="this.checkForm" class="form-filter">
				
			<p>
				<label for="title">Buscar Por</label>
				<input id="title" v-model="title" type="text" name="title">
			</p>

			<p>
				<label for="author">Autor</label>
				<select v-model="author" id="author" name="author">
					<option value="0" selected="selected">Selecione o autor</option>
			    	<option v-for="author in authors" :value="author.id">{{ author.name }}</option>
			    </select>
			</p>

			<p>
				<input type="submit" value="Enviar">
			</p>

			<p>
				<span v-for="error in errors">{{ error }}</span>
			</p>
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
