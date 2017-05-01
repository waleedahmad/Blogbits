<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\FacebookAlbum;
use App\Models\Post;
use DirkGroenen\Pinterest\Pinterest;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tumblr\API\Client as TumblrAPI;
use App\Http\Requests;

class APIController extends Controller
{
    protected $client;
    protected $fb;
    protected $pinterest;

    public function __construct()
    {
        $this->client = new TumblrAPI(
            env('TUMBLR_CONSUMER_KEY'), // Consumer Key
            env('TUMBLR_CONSUMER_SECRET'), // Consumer Secret
            env('TUMBLR_TOKEN'), // Token
            env('TUMBLR_TOKEN_SECRET')  // Token Secret
        );

        $this->fb = new \Facebook\Facebook([
            'app_id' => env('FACEBOOK_CLIENT_ID'),
            'app_secret' => env('FACEBOOK_CLIENT_SECRET'),
            'default_graph_version' => 'v2.6',
            'default_access_token' => $this->getUserAccessToken(),
        ]);

        $this->pinterest = new Pinterest(env('PINTEREST_APP_ID'), env('PINTEREST_APP_SECRET'));
    }

    /**
     * Returns Authenticated user facebook token
     * @return mixed
     */
    public function getUserAccessToken(){
        return Auth::user()->token;
    }

    /**
     * Publish a post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function publishBlogPost(Request $request){
        $post_id = $request->input('post_id');
        $post = Post::find($post_id);

        if($post){
            if($this->createBlogPost($post)){
                $this->deleteImage($post->file_name, $post->type);
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
        $posts = Post::where('type','=','tumblr')->take($this->getBatchPostLimit())->get();

        foreach ($posts as $post){
            if($this->createBlogPost($post)){
                $post->delete();
                $this->deleteImage($post->file_name, $post->type);
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
    public function createBlogPost($post){

        $config = $this->getConfig();
        if($this->client->createPost($config['active_blog'], [
            'type'      =>  'photo',
            'tags'      =>  $post->tags,
            'slug'      =>  $post->caption,
            'caption'   =>  '<a href="'.$config['post_link'].'">'.$post->caption.'</a>',
            'data64'    =>  base64_encode($this->getImage($post->file_name, 'tumblr')),
            'link'      =>  $config['post_link'],
            'source_url'    =>  'http://'.$config['active_blog']
        ])){
            return true;
        }

        return false;
    }

    /**
     * Show Tumblr blog posts
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function getPosts(Request $request){
        $offset = ($request->offset) ? $request->offset : 0;
        $config = $this->getConfig();
        $posts = $this->client->getBlogPosts($config['active_blog'], [
            'limit' =>  20,
            'offset'    =>  $offset,
            'tag'    =>  'models'
        ]);
        return view('blog_posts')->with('blog', $posts)->with('offset', $offset + 5);
    }

    /**
     * Public social post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function publishSocialPost(Request $request){
        $post_id = $request->input('post_id');
        $post = Post::find($post_id);

        if($post->count()){
            if($this->createSocialPost($post)){
                $this->deleteImage($post->file_name, $post->type);
                $post->delete();
                return response()->json(true);
            }
        }
        return response()->json(true);
    }

    /**
     * Create Posts for Social Pages (Facebook, Pinterest)
     * @param $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSocialPost($post){

        if($post->type === 'facebook'){
            $fb_post = $this->publishToFacebook($post);

            if($fb_post['id']){
                return response()->json(true);
            }
            return response()->json(false);
        }

        if($post->type === 'pinterest'){
            $pin = $this->publishToPinterest($post);

            if($pin['id']){
                return response()->json(true);
            }
            return response()->json(false);
        }
    }

    /**
     * Uploads a new photo to Facebook Album
     * @param $post
     * @return array
     */
    public function publishToFacebook($post){
        $config = $this->getConfig();
        $album_id = $this->getAlbumID($post->caption);

        $post = $this->fb->post('/' .$album_id. '/photos', [
            'message' => $post->caption,
            'source'    =>  $this->fb->fileToUpload(url('/').$post->uri)
        ], $this->getPageAccessToken($config['facebook_pageid']));

        return $post->getGraphNode()->asArray();
    }

    /**
     * Get Facebook photos album ID
     * @param $name
     * @return bool
     */
    public function getAlbumID($name){
        $id = $this->ifAlbumExistGetID($name);
        if($id){
            return $id;
        }else{
            $id = $this->createFacebookAlbum($name);
            $this->createLocalAlbumRecord($name, $id);
            return $id;
        }
    }

    /**
     * Create a photos album on Facebook
     * @param $name
     * @return mixed
     */
    public function createFacebookAlbum($name){
        $config = $this->getConfig();
        $album = $this->fb->post('/' . $config['facebook_pageid']. '/albums', [
            'name' => $name,
        ], $this->getPageAccessToken($config['facebook_pageid']));

        return $album->getDecodedBody()['id'];
    }

    /**
     * Create facebook album record in database
     * @param $name
     * @param $id
     */
    public function createLocalAlbumRecord($name, $id){
        $album = new FacebookAlbum();
        $album->name = $name;
        $album->album_id = $id;
        $album->save();
    }


    /**
     * Get Facebook album ID in database
     * @param $name
     * @return bool
     */
    public function ifAlbumExistGetID($name){
        $albums = FacebookAlbum::where('name','=',$name);

        if($albums->count()){
            return $albums->first()->album_id;
        }
        return false;
    }

    /**
     * Sync Facebook in database
     */
    public function syncFacebookAlbums(){
        FacebookAlbum::truncate();
        $albums = $this->getFacebookAlbums();

        foreach($albums as $album){
            $falbum = new FacebookAlbum();
            $falbum->name = $album['name'];
            $falbum->album_id = $album['id'];
            $falbum->save();
        }

        return response()->json(true);
    }

    /**
     * Get Facebook page access token
     * @param $page_id
     * @return mixed
     */
    public function getPageAccessToken($page_id){
        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $this->fb->get('/me/accounts', $this->getUserAccessToken());
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $pages = $response->getGraphEdge()->asArray();

        foreach ($pages as $key) {
            if ($key['id'] == $page_id) {
                return $key['access_token'];
            }
        }
    }


    /**
     * Get Facebook album
     * @return array
     */
    public function getFacebookAlbums(){
        $config = $this->getConfig();
        $albums = $this->fb->get('/' . $config['facebook_pageid']. '/albums?limit=100', $this->getPageAccessToken($config['facebook_pageid']));

        $albums = $albums->getGraphEdge();

        $allAlbums = array();

        if ($this->fb->next($albums)) {
            $albumsArray = $albums->asArray();
            $allAlbums = array_merge($allAlbums, $albumsArray);
            while ($albums = $this->fb->next($albums)) {
                $albumsArray = $albums->asArray();
                $allAlbums = array_merge($allAlbums, $albumsArray);
            }
        } else {
            $albumsArray = $albums->asArray();
            $allAlbums = array_merge($allAlbums, $albumsArray);
        }

        return $allAlbums;
    }

    /**
     * Upload image to pinterest board
     * @param $post
     * @return array
     */
    public function publishToPinterest($post){
        $config = $this->getConfig();
        $this->pinterest->auth->setOAuthToken($config['pinterest_token']);
        return $this->pinterest->pins->create(array(
            "note"          => $post->caption,
            "image"         => storage_path('app/public/posts/'.$post->type.'/'.$post->file_name),
            "board"         => $config['pinterest_username'].'/'.$config['pinterest_board']
        ))->toArray();
    }

    /**
     * Get image from storage
     * @param $file_name
     * @param $type
     * @return mixed
     */
    public function getImage($file_name,$type){
        return Storage::disk('local')->get('/public/posts/'.$type.'/'.$file_name);
    }

    /**
     * Delete image from storage
     * @param $file_name
     * @param $type
     * @return mixed
     */
    public function deleteImage($file_name, $type){
        return Storage::disk('local')->delete('/public/posts/'.$type.'/'.$file_name);
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
            'facebook_pageid'   =>  Config::where('name','=','facebook_pageid')->first()->value,
            'pinterest_username' => Config::where('name','=','pinterest_username')->first()->value,
            'pinterest_board' =>  Config::where('name','=','pinterest_board')->first()->value,
            'pinterest_token' =>  Config::where('name','=','pinterest_token')->first()->value,
        ];
    }
}
