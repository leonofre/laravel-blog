@extends('layouts.app')

@section('content')
    <posts-loop class="posts-wrapper" id="posts-wrapper">
    </posts-loop>
    <posts-filter class="posts-filter" id="posts-filter">
    </posts-filter>
    <navigation-links id="navigation-links">
    </navigation-links>
@endsection
