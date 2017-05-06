<?php

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::group([
    'middleware'    =>  'guest',
    'namespace'     =>  'Auth'
], function(){
    Route::get('auth/facebook', 'RegisterController@redirectToFacebookProvider');
    Route::get('auth/facebook/callback', 'RegisterController@handleFacebookProviderCallback');
    Route::get('auth/google', 'RegisterController@redirectToGoogleProvider');
    Route::get('auth/google/callback', 'RegisterController@handleGoogleProviderCallback');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/api/posts/facebook', 'ContentController@getFacebookContent');
    Route::get('/api/posts/pinterest', 'ContentController@getPinterestContent');
    Route::get('/api/posts/tumblr', 'ContentController@getTumblrContent');
    Route::get('/api/blog/feed', 'APIController@getTumblrFeed');
    Route::delete('/api/posts', 'ContentController@deletePost');
    Route::get('/api/posts/count', 'ContentController@getPostsCount');

    Route::post('/social/sync/fbAlbums', 'APIController@syncFacebookAlbums');

    Route::get('/content/sync', 'SyncController@syncData');
    Route::get('/content/sync/{service}', 'SyncController@syncSocialContent');

    Route::delete('/content/delete', 'ContentController@deletePost');
    Route::get('/content/edit/{id}', 'ContentController@editPost');
    Route::post('/content/update', 'ContentController@updatePost');

    Route::post('/content/post/tumblr', 'APIController@publishBlogPost');
    Route::post('/content/post/facebook', 'APIController@publishSocialPost');
    Route::post('/content/post/pinterest', 'APIController@publishSocialPost');


    Route::post('/content/post/batch', 'APIController@publishPostBatch');
    Route::get('/content/user/followers', 'APIController@getClient');
    Route::delete('/content/deleteAll/{type}', 'ContentController@deleteAllPosts');
    Route::post('/content/update/tags', 'ContentController@updateTags');
    Route::post('/content/backup', 'ContentController@backupAllPosts');
    Route::get('/config/', 'ConfigController@configView');
    Route::post('/config/user', 'ConfigController@userConfig');
    Route::post('/config/scheduler/timings', 'ConfigController@updateSchedulerTimings');
    Route::post('/config/{type}', 'ConfigController@config');
    Route::get('/config/posts/batchLimit','ConfigController@getBatchPostLimit');

    Route::get('{all?}', function(){
        return view('layouts.spa');
    })->where('all', '([A-z\d-\/_.]+)?');

});

