<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tumblr\API\Client;

class ContentController extends Controller
{
    /**
     * Render posts
     * @return $this
     */
    public function index(){
        $posts = Post::simplePaginate(10);
        return view('index')->with('posts', $posts);
    }
    
    /**
     * Delete a single post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePost(Request $request){
        $post_id = $request->input('post_id');
        $post = Post::where('id','=',$post_id);

        if($post->count()){
            $post = $post->first();

            if($this->deleteImage($post->file_name)){
                if($post->delete()){
                    return response()->json(true);
                }
            }
        }

        return response()->json(false);
    }

    /**
     * Delete all posts
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAllPosts(){
        $posts = Post::all();

        foreach($posts as $post){
            $this->deleteImage($post->file_name);
        }

        if(Post::truncate()){
            return response()->json(true);
        }

        return response()->json(false);
    }

    /**
     * Removes image from storage
     * @param $name
     * @return mixed
     */
    public function deleteImage($name){
        return Storage::disk('local')->delete('/public/posts/'.$name);
    }

    public function updateTags(Request $request){
        $tags = $request->input('tags');
        $post_id = $request->input('post_id');

        Post::where('id','=', $post_id)->update([
            'tags'  => $tags
        ]);
    }
}
