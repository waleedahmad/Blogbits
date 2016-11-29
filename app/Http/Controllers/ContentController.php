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
     * Render tumblr posts
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function blogContent(){
        $posts = Post::where('type','=','blog')->simplePaginate(10);
        return view('index')->with('posts', $posts);
    }

    /**
     * Render social posts
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function socialContent(){
        $posts = Post::where('type','=','social')->inRandomOrder()->simplePaginate(10);
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

            if($this->deleteImage($post->file_name, $post->type)){
                if($post->delete()){
                    return response()->json(true);
                }
            }
        }

        return response()->json(false);
    }

    /**
     * Delete all posts
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAllPosts($type){
        $posts = Post::where('type','=', $type)->get();

        foreach($posts as $post){
            $this->deleteImage($post->file_name, $post->type);
        }

        if(Post::where('type','=',$type)->delete()){
            return response()->json(true);
        }

        return response()->json(false);
    }

    /**
     * Removes image from storage
     * @param $name
     * @param $type
     * @return mixed
     */
    public function deleteImage($name, $type){
        return Storage::disk('local')->delete('/public/posts/'.$type.'/'.$name);
    }

    public function updateTags(Request $request){
        $tags = $request->input('tags');
        $post_id = $request->input('post_id');

        Post::where('id','=', $post_id)->update([
            'tags'  => $tags
        ]);
    }

    public function editPost($id){
        $post = Post::where('id', '=', $id)->first();
        return view('edit_post')->with('post' , $post);
    }

    public function updatePost(Request $request){
        $id = $request->input('id');
        $caption = $request->input('caption');

        if(Post::where('id', '=', $id)->update([
            'caption'   =>  $caption
        ])){
            return redirect('/');
        }
    }
}
