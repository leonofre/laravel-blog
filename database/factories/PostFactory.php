<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use Faker\Generator as Faker;

use Faker\Provider\Lorem;

$factory->define(Post::class, function (Faker $faker) {
	$title       = $faker->sentence( 5 );
	$description = Lorem::paragraph( 50 );
    return [
        'title'       => $title,
        'slug'        => Str::slug( $title, '-' ),
        'description' => $description,
        'excerpt'     => Str::words( $description, 15 ),
        'image'       => 'https://cdn.pixabay.com/photo/2015/02/24/15/41/dog-647528_960_720.jpg',
    ];
});
