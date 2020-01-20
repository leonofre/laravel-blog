<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'excerpt',
        'image',
        'author',
        'author_id',
    ];

    public function User(){
	    return $this->belongsTo('App\User','author_id');
	}
}
