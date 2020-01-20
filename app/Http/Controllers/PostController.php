<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Illuminate\Routing\Route;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);
  
        $response = Post::create( $request->all() );
   
        return $response;
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
    	$posts = Post::where( 'title', 'like', "%$request->search%" )->where( 'description', 'like', "%$request->search%" )->where( 'author_id', '=', $request->author )->paginate( $posts_count, ['*'], 'page', $page );

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $image = $request->post['image'];  // your base64 encoded
        // $image = str_replace( 'data:image/png;base64,', '', $image );
        // $image = str_replace( ' ', '+', $image );
        $image_name = $request->image_name;
        $path = '/images/' . $image_name;
        // $file =Image::make( file_get_contents( $data->base64_image ) )->save( $path );

        if ( preg_match( '/^data:image\/(\w+);base64,/', $image ) ) {
		    $data = substr( $image, strpos( $image, ',' ) + 1 );

		    $data = base64_decode( $data );
		    $file = \Storage::disk( 'public' )->put( $path, $data);
        	var_dump( $file );
		}
    	// $file->move( public_path('uploads'), $request->image_name );

    	var_dump( $request->post['image'] );
        return 'ok';
        // $request->validate([
        //     'file' => 'required|mimes:pdf,xlx,csv|max:2048',
        // ]);
  
        // $fileName = time().'.'.$request->file->extension();  
   
        // $request->file->move(public_path('uploads'), $fileName);
   
        // return back()
        //     ->with( 'success','You have successfully upload file.' )
        //     ->with( 'file',$fileName );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
