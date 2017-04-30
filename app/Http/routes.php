<?php

Route::auth();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'ContentController@blogContent');
    Route::get('/social', 'ContentController@socialContent');
    Route::post('/social/sync/fbAlbums', 'APIController@syncFacebookAlbums');
    Route::get('/content/sync', 'SyncController@syncData');
    Route::get('/content/sync/social', 'SyncController@syncSocialData');
    Route::delete('/content/delete', 'ContentController@deletePost');
    Route::get('/content/edit/{id}', 'ContentController@editPost');
    Route::post('/content/update', 'ContentController@updatePost');
    Route::post('/content/post/blog', 'APIController@publishBlogPost');
    Route::post('/content/post/social', 'APIController@publishSocialPost');
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

    Route::get('/albums', 'APIController@syncFacebookAlbums');

    Route::get('/tumblr', 'APIController@getPosts');
    Route::get('/tumblr/posts/all', 'APIController@getAllPost');


});

Route::group([
    'middleware'    =>  'guest',
    'namespace'     =>  'Auth'
], function(){
    Route::get('auth/facebook', 'AuthController@redirectToFacebookProvider');
    Route::get('auth/facebook/callback', 'AuthController@handleFacebookProviderCallback');
    Route::get('auth/google', 'AuthController@redirectToGoogleProvider');
    Route::get('auth/google/callback', 'AuthController@handleGoogleProviderCallback');
});

