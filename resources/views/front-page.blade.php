@extends('layouts.app')

@section('content')

    <div class="content">
        <div class="title m-b-md">
            Laravel
        </div>

        <div class="links">
            <a href="{{ route( 'posts' ) }}">Blog</a>
        </div>
    </div>
@endsection
