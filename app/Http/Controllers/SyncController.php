<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Post;
use App\Http\Requests;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SyncController extends Controller
{
    /**
     * Sync all files
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncData(){
        $images = $this->getAllImages();

        shuffle($images);

        foreach ($images as $image) {
            if($this->isExtAllowed($image) && $this->getFileSizeMB($image) < 10){
                $this->createPost($image);
                $this->removeFile($image);
            }else{
                $this->removeFile($image);
            }
        }
        
        return response()->json(true);
    }

    /**
     * Validate extension
     * @param $image
     * @return bool
     */
    public function isExtAllowed($image){
        $allowed = ['gif', 'jpeg', 'png', 'bmp', 'jpg'];
        return (in_array($this->getFileExtension($image),$allowed));
    }

    /**
     * Create a new post
     * @param $image
     * @return bool
     */
    public function createPost($image){

        $caption=   $this->getSafeImageName($image);
        $ext    = 	$this->getFileExtension($image);
        $id     =   $this->getRandomID();
        $uri 	= 	'/uploads/'.$id.'.'.$ext;
        $tags   =   $caption. ','.Config::where('name','=', 'default_tags')->first()->value;

        $post = new Post([
            'caption'   =>  $caption,
            'file_name' =>  $id.'.'.$ext,
            'uri'       =>  $uri,
            'tags'      =>  $tags
        ]);

        if($post->save()){
            $this->saveFile($image, $id, $ext);
            return true;
        }
        return false;
    }

    /**
     * Save synced file
     * @param $file
     * @param $id
     * @param $ext
     */
    public function saveFile($file, $id, $ext){
        Storage::disk('local')->put('/public/posts/'.$id.'.'.$ext,  File::get($file));
    }

    /**
     * Remove synced file
     * @param $file
     * @return mixed
     */
    public function removeFile($file){
        return File::delete($file);
    }

    /**
     * Get file extension
     * @param $file
     * @return string
     */
    public function getFileExtension($file){
        return strtolower(File::extension($file));
    }

    /**
     * Generate random ID
     * @return string
     */
    public function getRandomID(){
        return str_random(15);
    }

    /**
     * Get all images from sync folder
     * @return mixed
     */
    public function getAllImages(){
        return File::allFiles(env('SYNC_FOLDER'));
    }

    /**
     * Safe image file name
     * @param $file
     * @return string
     */
    protected function getSafeImageName($file){
        return trim(preg_replace(['/[^A-Za-z0-9\-]/', '/[0-9]+/'], ' ', basename( utf8_encode(File::name($file)))));
    }

    /**
     * Calculates file size
     * @param $file
     * @return mixed
     */
    function getFileSizeMB($file){
        return (File::size($file) * .0009765625) * .0009765625;
    }
    
}
