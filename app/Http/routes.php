<?php

Route::auth();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'ContentController@index');
    Route::get('/content/sync', 'SyncController@syncData');
    Route::delete('/content/delete', 'ContentController@deletePost');
    Route::post('/content/post', 'APIController@publishPost');
    Route::post('/content/post/batch', 'APIController@publishPostBatch');
    Route::get('/content/user/followers', 'APIController@getClient');
    Route::delete('/content/deleteAll', 'ContentController@deleteAllPosts');
    Route::post('/content/update/tags', 'ContentController@updateTags');
    Route::get('/config/', 'ConfigController@configView');
    Route::post('/config/user', 'ConfigController@userConfig');
    Route::post('/config/scheduler/timings', 'ConfigController@updateSchedulerTimings');
    Route::post('/config/{type}', 'ConfigController@config');
    Route::get('/config/posts/batchLimit','ConfigController@getBatchPostLimit');
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

