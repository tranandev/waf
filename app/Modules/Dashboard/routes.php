<?php
$namespace = 'App\Modules\Dashboard\Controllers';
Route::group(
    ['module'=>'Dashboard', 'namespace' => $namespace, 'middleware' => 'web'],
    function() {
    Route::get('/', [
            'middleware' => 'auth',
            'as' => "dashboard",
            'uses' => 'DashboardController@index'
        ]);


	Route::get('/dashboard', [
            'middleware' => 'auth',
            'as' => "dashboard",
            'uses' => 'DashboardController@index'
        ]);

    Route::get('/get-resource', [
            'middleware' => 'auth',
            'as' => "get-resource",
            'uses' => 'DashboardController@get_resource'
        ]);

    Route::get('/get-old-resource', [
            'middleware' => 'auth',
            'as' => "get-old-resource",
            'uses' => 'DashboardController@get_old_resource'
        ]);


    }
);
