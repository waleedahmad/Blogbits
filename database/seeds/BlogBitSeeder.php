<?php

use Illuminate\Database\Seeder;
use App\Models\Config;

class BlogBitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::truncate();
        
        Config::create([
            'name'   =>  'active_blog',
            'value' => env('TUMBLR_BLOG', '')
        ]);

        Config::create([
            'name'   =>  'post_link',
            'value' => env('POST_LINK', '')
        ]);


        Config::create([
            'name'   =>  'pinterest',
            'value' => env('PINTEREST', '')
        ]);


        Config::create([
            'name'   =>  'facebook',
            'value' => env('FACEBOOK', '')
        ]);

        Config::create([
            'name'   =>  'sync_folder',
            'value' => env('SYNC_FOLDER', '')
        ]);

        Config::create([
            'name'  =>  'default_tags',
            'value' =>  'models'
        ]);

        Config::create([
            'name'  =>  'batch_post_limit',
            'value' =>  5
        ]);
    }
}
