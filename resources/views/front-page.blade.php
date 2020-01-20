@extends('layouts.app')

@section('content')

    <div class="container">
        <h1 class="title m-b-md">
            Teste Prático
        </h1>

        <p>
        	Clique no link "Blog", presente no menu, para ir para a lista de Posts.
        </p>
        @if (Route::has('login'))
        	@auth
	        	<p>
		        	Ou se preferir, clique no item "Perfil" e clique no seu nome para ir para o Dashboard ou clique em Logout, para sair da sua conta.
		        </p>
	        @else
		        <p>
		        	Ou se preferir, clique no item "Perfil" e faça Login ou se Registre.
		        </p>
	        @endauth
	    @endif
    </div>
@endsection
