<?php

namespace App\Console\Commands;

use App\Config;
use App\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Tumblr\API\Client as TumblrAPI;


class PublishPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new TumblrAPI(
            env('TUMBLR_CONSUMER_KEY'), // Consumer Key
            env('TUMBLR_CONSUMER_SECRET'), // Consumer Secret
            env('TUMBLR_TOKEN'), // Token
            env('TUMBLR_TOKEN_SECRET')  // Token Secret
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->publishPostBatch();
    }

    /**
     * Post a batch of posts
     * @return \Illuminate\Http\JsonResponse
     */
    public function publishPostBatch(){
        $posts = Post::where('type','=','tumblr')->take($this->getBatchPostLimit())->get();
        foreach ($posts as $post){
            if($this->createPost($post)){
                $post->delete();
                $this->deleteImage($post->uri);
            }
        }
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
            'data64'    =>  base64_encode($this->getImage($post->uri)),
            'link'      =>  $config['post_link'],
            'source_url'    =>  'http://'.$config['active_blog']
        ])){
            return true;
        }

        return false;
    }

    /**
     * Get Batch post limit
     * @return mixed
     */
    public function getBatchPostLimit(){
        return Config::where('name','=','batch_post_limit')->first()->value;
    }

    /**
     * Delete image from storage
     * @param $uri
     * @return mixed
     */
    public function deleteImage($uri){
        return Storage::disk('public')->delete($uri);
    }

    /**
     * Get image from storage
     * @param $uri
     * @return mixed
     */
    public function getImage($uri){
        return Storage::disk('public')->get($uri);
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
