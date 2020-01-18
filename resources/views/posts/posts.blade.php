<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <header class="container-full flex-center position-ref">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif


        </header>
        <main class="container-full flex-center position-ref">
            <section class="posts-wrapper">
                @foreach ( $posts as $post )
                    <article id="post-{{ $post->id }}" class="post">
                        <img src="{{ $post->image }}" alt="">
                        <div class="content">
                            <h2>{{ $post->title }}</h2>
                            <p>{{ $post->excerpt }}</p>
                            <small>{{ $post->author }}</small>
                        </div>
                    </article>
                @endforeach
            </section>

            {{ $posts->links() }}
        </main>
        
    </body>
</html>
