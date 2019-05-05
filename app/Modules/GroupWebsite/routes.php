<?php
$namespace = 'App\Modules\GroupWebsite\Controllers';
Route::group(
    ['module'=>'GroupWebsite', 'namespace' => $namespace, 'middleware' => 'web'],
    function() {
	Route::get('/group-website', [
            'middleware' => 'auth',
            'as' => "group-website",
            'uses' => 'GroupWebsiteController@index'
        ]);

    Route::get('/get-data-table', [
            'middleware' => 'auth',
            'as' => "get-dataTable",
            'uses' => 'GroupWebsiteController@getTableData'
        ]);

	Route::post('/add-group-website', [
            'middleware' => 'auth',
            'as' => "add-group-website",
            'uses' => 'GroupWebsiteController@addGroup'
        ]);

    Route::post('/update-group-website', [
            'middleware' => 'auth',
            'as' => "update-group-website",
            'uses' => 'GroupWebsiteController@updateGroup'
        ]);

    Route::post('/delete-group-website', [
            'middleware' => 'auth',
            'as' => "delete-group-website",
            'uses' => 'GroupWebsiteController@deleteGroup'
        ]);

    }
);
