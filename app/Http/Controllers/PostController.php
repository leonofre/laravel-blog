<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $posts_count, $page )
    {
    	$posts = Post::paginate( $posts_count, ['*'], 'page', $page );

    	return ! empty( $posts->items() ) ? response( $posts, 200 ) : response( $posts, 204 );
    }

    /**
     * Display the filtered resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $posts_count
     * @param  int  $page
     * @return \Illuminate\Http\Response
     */
    public function search( Request $request, $posts_count, $page )
    {
        if ( $request->author ) {
            $posts = Post::where([
                ['author_id', '=', $request->author],
                ['title', 'like', "%$request->search%"] 
            ])->orWhere( [
                ['author_id', '=', $request->author],
                ['description', 'like', "%$request->search%"] 
            ])->paginate( $posts_count, ['*'], 'page', $page );
        } else {
            $posts = Post::where( 'title', 'like', "%$request->search%" )->orWhere( 'description', 'like', "%$request->search%" )->paginate( $posts_count, ['*'], 'page', $page );
        }

        return ! empty( $posts->items() ) ? response( $posts, 200 ) : response( $posts, 204 );
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show( $slug )
    {
        $post      = Post::where( 'slug', '=', $slug )->first();

        if ( empty( $post ) ) {
        	return response( $post, 204 );
        }

        $prev      = Post::where( 'id', '<', $post->id )->orderBy( 'id', 'DESC' )->first();
        $next      = Post::where( 'id', '>', $post->id )->orderBy( 'id' )->first();

        $post->url       = url( '/blog' ) . "/$slug";
        if ( ! empty( $next ) ) {
	        $post->next_link = url( '/blog' ) . "/$next->slug";
	        $post->next_name = $next->title;
        }

        if ( ! empty( $prev ) ) {
	        $post->prev_link = url( '/blog' ) . "/$prev->slug";
	        $post->prev_name = $prev->title;
        }

        return response( $post, 200 );
    }


    /**
     * Display the paginated posts of current user.
     *
     * @param  int  $posts_count
     * @param  int  $page
     * @return \Illuminate\Http\Response
     */
    public function user_posts( $posts_count, $page )
    {
    	$posts = Post::where( 'author_id', '=', \Auth::user()->id )->paginate( $posts_count, ['*'], 'page', $page );

        return ! empty( $posts->items() ) ? response( $posts, 200 ) : response( $posts, 204 );
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function user_post( $id )
    {
        $post      = Post::where( 'id', '=', $id )->first();

        if ( empty( $post ) ) {
        	return response( $post, 204 );
        }
       
        $post->url       = url( '/blog' ) . "/$post->slug";

        return response( $post, 200 );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $posts_count
     * @param  int  $page
     * @return \Illuminate\Http\Response
     */
    public function user_search( Request $request, $posts_count, $page )
    {
// var_dump( \Auth::user()->id );

        $posts = Post::where([
            ['author_id', '=', \Auth::user()->id],
            ['title', 'like', "%$request->search%"] 
        ])->orWhere( [
            ['author_id', '=', \Auth::user()->id],
            ['description', 'like', "%$request->search%"] 
        ])->paginate( $posts_count, ['*'], 'page', $page );

        return ! empty( $posts->items() ) ? response( $posts, 200 ) : response( $posts, 204 );
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $is_valid = $request->validate([
            'image_size'       => 'numeric|max:4096000|min:1',
            'post.title'       => 'required',
            'post.description' => 'required',
        ]);

        if ( ! $is_valid ) {
            return response( $is_valid->errors(), 500 );
        }


        $post_data            = $request->post;
        $post_data['excerpt'] = strip_tags( Str::words( $post_data['description'], 15 ) );
        $slug                 = Str::slug( $post_data['title'], '-' );
        $count                = 0;

        while ( Post::where( 'slug', '=', $slug )->first() ) {
            $slug  = $slug . "-$count";
            $count++;
        }
        
        $post_data['slug']    = $slug;
        $post_data['author']  = \Auth::user()->name;
        $post_data['author_id']  = \Auth::user()->id;
        
        if ( $request->image_name ) {
            $image_name           = explode( '.', $request->image_name );
            $image_name           = $image_name[0] . '-' . now() . ".$image_name[1]";
            $path                 = public_path(). "/images/$image_name";
            $link                 = url( '/' ). "/images/$image_name";
            try{
                $post_data['image']   = $link;
                $image                = $request->post['image'];
                $image                = str_replace( 'data:image/png;base64,', '', $image );
                $image                = str_replace( ' ', '+', $image );
                $file                 = \File::put( $path, base64_decode( $image ) );
            } catch( \FileException $e ) {
                return response( $e, 500 );
            }
        }

        $post = Post::create( $post_data );
  

        return response( $post, 200 );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {

        $is_valid = $request->validate([
            'image_size'       => 'numeric|max:4096000',
            'post.title'       => 'required',
            'post.description' => 'required',
        ]);

        if ( ! $is_valid ) {
        	return response( $is_valid->errors(), 500 );
        }


        $post_data            = $request->post;
        $post_data['excerpt'] = strip_tags( Str::words( $post_data['description'], 15 ) );
        
        if ( $request->image_name ) {
	        $image_name           = explode( '.', $request->image_name );
	        $image_name           = $image_name[0] . '-' . now() . ".$image_name[1]";
	        $path                 = public_path(). "/images/$image_name";
	        $link                 = url( '/' ). "/images/$image_name";
	        try{
	        	$post_data['image']   = $link;
		        $image                = $request->post['image'];
		        $image                = str_replace( 'data:image/png;base64,', '', $image );
		        $image                = str_replace( ' ', '+', $image );
		        $file                 = \File::put( $path, base64_decode( $image ) );
	        } catch( \FileException $e ) {
	        	return response( $e, 500 );
	        }
        }


        $post = Post::find( $request->post['id'] );
        $post->update( $post_data );
  

        return response( $post, 200 );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        $post = Post::where( 'id', $id )->delete();

        return response( $post, 200 );
    }
}
