<?php
$namespace = 'App\Modules\Website\Controllers';
Route::group(
    ['module'=>'Website', 'namespace' => $namespace, 'middleware' => 'web'],
    function() {
	Route::get('/website', [
            'middleware' => 'auth',
            'as' => "website",
            'uses' => 'WebsiteController@index'
        ]);

	Route::get('/get-website-dataTable', [
            'middleware' => 'auth',
            'as' => "get-website-dataTable",
            'uses' => 'WebsiteController@getWebsiteTableData'
        ]);

	Route::post('/add-website', [
            'middleware' => 'auth',
            'as' => "add-website",
            'uses' => 'WebsiteController@addWebsite'
        ]);

    Route::post('/update-website', [
            'middleware' => 'auth',
            'as' => "update-website",
            'uses' => 'WebsiteController@updateWebsite'
        ]);

    Route::post('/delete-website', [
            'middleware' => 'auth',
            'as' => "delete-website",
            'uses' => 'WebsiteController@deleteWebsite'
        ]);

    Route::post('/check-rule', [
            'middleware' => 'auth',
            'as' => "check-rule",
            'uses' => 'WebsiteController@checkRule'
        ]);

    }
);
