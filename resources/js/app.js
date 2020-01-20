/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

console.log( ROUTE );

if ( 'posts' === ROUTE ) {
	require( './blog' );
	require( './navigation-links' );
}


if ( 'single-post' === ROUTE ) {
	require( './single' );
}

if ( 'home' === ROUTE ) {
	require( './user-posts' );
	require( './navigation-links' );
}

if ( 'edit-post' === ROUTE ) {
	require( './edit-post' );
}

if ( 'create-post' === ROUTE ) {
	require( './create-post' );
}
