<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tumblr\API\Client as TumblrAPI;
use App\Http\Requests;

class APIController extends Controller
{

    protected $client;

    public function __construct()
    {
        $this->client = new TumblrAPI(
            env('TUMBLR_CONSUMER_KEY'), // Consumer Key
            env('TUMBLR_CONSUMER_SECRET'), // Consumer Secret
            env('TUMBLR_TOKEN'), // Token
            env('TUMBLR_TOKEN_SECRET')  // Token Secret
        );
    }

    /**
     * Publish a post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function publishPost(Request $request){
        $post_id = $request->input('post_id');
        $post = Post::where('id','=', $post_id);

        if($post->count()){
            if($this->createPost($post->first())){
                $this->deleteImage($post->first()->file_name);
                $post->delete();
                return response()->json(true);
            }
        }
        return response()->json(true);
    }

    /**
     * Post a batch of posts
     * @return \Illuminate\Http\JsonResponse
     */
    public function publishPostBatch(){
        $posts = Post::take($this->getBatchPostLimit())->get();

        foreach ($posts as $post){
            if($this->createPost($post)){
                $post->delete();
                $this->deleteImage($post->file_name);
            }
        }
        return response()->json(true);
    }

    /**
     * Get Batch post limit
     * @return mixed
     */
    public function getBatchPostLimit(){
        return Config::where('name','=','batch_post_limit')->first()->value;
    }

    /**
     * Post to Tumblr
     * @param $post
     * @return bool
     */
    public function createPost($post){

        $config = $this->getConfig();
        if($this->client->createPost($config['active_blog'], [
            'type'      =>  'photo',
            'tags'      =>  $post->tags,
            'slug'      =>  $post->caption,
            'caption'   =>  '<a href="'.$config['post_link'].'">'.$post->caption.'</a>',
            'data64'    =>  base64_encode($this->getImage($post->file_name)),
            'link'      =>  $config['post_link'],
            'source_url'    =>  'http://'.$config['active_blog']
        ])){
            return true;
        }

        return false;
    }

    /**
     * Get image from storage
     * @param $file_name
     * @return mixed
     */
    public function getImage($file_name){
        return Storage::disk('local')->get('/public/posts/'.$file_name);
    }

    /**
     * Delete image from storage
     * @param $file_name
     * @return mixed
     */
    public function deleteImage($file_name){
        return Storage::disk('local')->delete('/public/posts/'.$file_name);
    }

    /**
     * Return blogbits app config array
     * @return array
     */
    public function getConfig(){
        return [
            'post_link'  => Config::where('name','=','post_link')->first()->value,
            'pinterest' =>  Config::where('name','=','pinterest')->first()->value,
            'facebook'  =>  Config::where('name','=','facebook')->first()->value,
            'active_blog' =>  Config::where('name','=','active_blog')->first()->value,
        ];
    }
}
