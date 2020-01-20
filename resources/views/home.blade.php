@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Meus Posts</div>

        <posts-loop class="card-body" id="user-posts">
        </posts-loop>
        <navigation-links id="navigation-links">
        </navigation-links>
    </div>
</div>
@endsection
