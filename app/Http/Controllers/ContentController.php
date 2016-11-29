<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

    /**
     * @param Request $request
     * Update blog post tags
     */
    public function updateTags(Request $request){
        $tags = $request->input('tags');
        $post_id = $request->input('post_id');

        Post::where('id','=', $post_id)->update([
            'tags'  => $tags
        ]);
    }

    /**
     * Edit posts
     * @param $id
     * @return $this
     */
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

    /**
     * Backup all posts to sync folders
     * @return \Illuminate\Http\JsonResponse
     */
    public function backupAllPosts(){
        $posts = Post::all();

        foreach($posts as $post){
            $post->delete();
            if(File::move(storage_path().'/app/public/posts/'.$post->type.'/'.$post->file_name , $this->getUniqueFileName($post->caption, $post->file_name, $post->type,1))){
                $post->delete();
            }
        }

        Post::truncate();

        return response()->json(true);
    }

    /**
     * Returns unique file name after checking in source location
     * @param $caption
     * @param $filename
     * @param $type
     * @param $counter
     * @return mixed|string
     */
    public function getUniqueFileName($caption, $filename, $type, $counter){
        $sync_folder = env('SYNC_FOLDER');
        $social_sync_folder = env('SOCIAL_SYNC_FOLDER');

        $path = ($type === 'blog') ? $sync_folder.'/' : $social_sync_folder;
        $path = $path.'/'.$caption.$counter.$this->getExtensionFromFileName($filename);

        if(File::exists($path)) {
            return $this->getUniqueFileName($caption, $filename, $type, $counter + 1);
        }
        return $path;
    }

    /**
     * Get extension from filename
     * @param $name
     * @return string
     */
    public function getExtensionFromFileName($name){
        return '.'.File::extension($name);
    }
}
