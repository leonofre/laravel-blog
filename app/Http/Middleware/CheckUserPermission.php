<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Post;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $post = Post::find( $request->id );

        return $post->author_id !== \Auth::user()->id ? redirect( 'home' ) : $next( $request );
    }
}
