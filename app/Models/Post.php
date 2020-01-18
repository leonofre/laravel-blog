<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'description',
        'excerpt',
        'image',
    ];

    public function User(){
	    return $this->belongsTo('App\User','author_id');
	}
}
