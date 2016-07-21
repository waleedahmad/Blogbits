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
            'name'      =>  'active_blog',
            'value'     =>  env('TUMBLR_BLOG', ''),
            'type'      =>  'blog'
        ]);

        Config::create([
            'name'      =>  'post_link',
            'value'     => env('POST_LINK', ''),
            'type'      =>  'blog'
        ]);


        Config::create([
            'name'      =>  'pinterest',
            'value'     =>  env('PINTEREST', ''),
            'type'      =>  'blog'
        ]);


        Config::create([
            'name'      =>  'facebook',
            'value'     =>  env('FACEBOOK', ''),
            'type'      =>  'blog'
        ]);

        Config::create([
            'name'      =>  'sync_folder',
            'value'     =>  env('SYNC_FOLDER', ''),
            'type'      =>  'blog'
        ]);

        Config::create([
            'name'      =>  'default_tags',
            'value'     =>  'models',
            'type'      =>  'blog'
        ]);

        Config::create([
            'name'      =>  'batch_post_limit',
            'value'     =>  5,
            'type'      =>  'scheduler'
        ]);

        Config::create([
            'name'      =>  'scheduler_frequency',
            'value'     =>  'hourly',
            'type'      =>  'scheduler'
        ]);

        Config::create([
            'name'      =>  'scheduler_start_time',
            'value'     =>  0,
            'type'      =>  'scheduler'
        ]);

        Config::create([
            'name'      =>  'scheduler_end_time',
            'value'     =>  23,
            'type'      =>  'scheduler'
        ]);
    }
}
