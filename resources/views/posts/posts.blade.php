@extends('layouts.app')

@section('content')
    <posts-filter class="posts-filter" id="posts-filter">
    </posts-filter>
    <posts-loop class="posts-wrapper" id="posts-wrapper">
    </posts-loop>
    <navigation-links id="navigation-links">
    </navigation-links>
@endsection
