<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Render tumblr posts
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTumblrContent(Request $request){
        $posts = Post::where('type','=','tumblr')->skip($request->skip)->take($request->take)->get()->toArray();
        return response()->json([
            'posts' =>  $posts,
            'total' =>  Post::where('type','=','facebook')->count()
        ]);
    }

    /**
     * Render social posts
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFacebookContent(Request $request){
        $posts = Post::where('type','=','facebook')->skip($request->skip)->take($request->take)->get()->toArray();
        return response()->json([
            'posts' =>  $posts,
            'total' =>  Post::where('type','=','facebook')->count()
        ]);
    }


    public function getPinterestContent(Request $request){
        $posts = Post::where('type','=','pinterest')->skip($request->skip)->take($request->take)->get()->toArray();
        return response()->json([
            'posts' =>  $posts,
            'total' =>  Post::where('type','=','pinterest')->count()
        ]);
    }

    public function getPostsCount(){
        return response()->json([
            'tumblr'    =>  Post::where('type','=', 'tumblr')->count(),
            'facebook'    =>  Post::where('type','=', 'facebook')->count(),
            'pinterest'    =>  Post::where('type','=', 'pinterest')->count(),
        ]);
    }

    /**
     * Delete a single post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePost(Request $request){
        $post_id = $request->input('post_id');
        $post = Post::find($post_id);

        if($post){
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
        return Storage::disk('public')->delete($type.'/'.$name);
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

            if(File::exists(storage_path().'/app/public/'.$post->type.'/'.$post->file_name)){
                if(File::copy(storage_path().'/app/public/'.$post->type.'/'.$post->file_name , $this->getUniqueFileName($post->caption, $post->file_name, $post->type,1))){
                    if(File::delete(storage_path().'/app/public/'.$post->type.'/'.$post->file_name)){
                        $post->delete();
                    }
                }
            }
        }

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

        $folder = [
            'pinterest' =>  env('PINTEREST_SYNC_FOLDER'),
            'facebook'  =>  env('FACEBOOK_SYNC_FOLDER'),
            'tumblr'    =>  env('SYNC_FOLDER')
        ];

        $path = $folder[$type].'/'.$caption.$counter.$this->getExtensionFromFileName($filename);

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
