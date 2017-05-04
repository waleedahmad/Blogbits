<?php

use App\Config;
use Illuminate\Database\Seeder;

class BlogBitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::truncate();
        /**
         * Blog Config
         */
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
        /**
         * Blog Scheduler config
         */
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
        /**
         * Social Config
         */
        Config::create([
            'name'      =>  'social_scheduler_frequency',
            'value'     =>  'hourly',
            'type'      =>  'social'
        ]);
        Config::create([
            'name'      =>  'social_scheduler_start_time',
            'value'     =>  0,
            'type'      =>  'social'
        ]);
        Config::create([
            'name'      =>  'social_scheduler_end_time',
            'value'     =>  23,
            'type'      =>  'social'
        ]);
        Config::create([
            'name'      =>  'facebook_pageid',
            'value'     =>  env('FACEBOOK_PAGE_ID', ''),
            'type'      =>  'social'
        ]);
        Config::create([
            'name'      =>  'pinterest_username',
            'value'     =>  env('PINTEREST_USERNAME', ''),
            'type'      =>  'social'
        ]);
        Config::create([
            'name'      =>  'pinterest_board',
            'value'     =>  env('PINTEREST_BOARD', ''),
            'type'      =>  'social'
        ]);
        Config::create([
            'name'      =>  'pinterest_token',
            'value'     =>  env('PINTEREST_TOKEN', ''),
            'type'      =>  'social'
        ]);
        Config::create([
            'name'      =>  'facebook_sync_folder',
            'value'     =>  env('FACEBOOK_SYNC_FOLDER', ''),
            'type'      =>  'social'
        ]);
        Config::create([
            'name'      =>  'pinterest_sync_folder',
            'value'     =>  env('PINTEREST_SYNC_FOLDER', ''),
            'type'      =>  'social'
        ]);
    }
}
