<?php
$namespace = 'App\Modules\Rules\Controllers';
Route::group(
    ['module'=>'Rules', 'namespace' => $namespace, 'middleware' => 'web'],
    function() {
	Route::get('/rules', [
            'middleware' => 'auth',
            'as' => "rules",
            'uses' => 'RulesControllers@index'
        ]);

    Route::get('/get-ip-dataTable', [
            'middleware' => 'auth',
            'as' => "get-ip-dataTable",
            'uses' => 'IpController@getDataTable'
        ]);

    Route::post('/add-ip-rule', [
            'middleware' => 'auth',
            'as' => "add-ip-rule",
            'uses' => 'IpController@addRule'
        ]);

    Route::post('/update-ip-rule', [
            'middleware' => 'auth',
            'as' => "update-ip-rule",
            'uses' => 'IpController@updateRule'
        ]);

    Route::post('/delete-ip-rule', [
            'middleware' => 'auth',
            'as' => "delete-ip-rule",
            'uses' => 'IpController@deleteRule'
        ]);

    Route::get('/get-url-dataTable', [
            'middleware' => 'auth',
            'as' => "get-url-dataTable",
            'uses' => 'UrlController@getDataTable'
        ]);

    Route::post('/add-url-rule', [
            'middleware' => 'auth',
            'as' => "add-url-rule",
            'uses' => 'UrlController@addRule'
        ]);

    Route::post('/update-url-rule', [
            'middleware' => 'auth',
            'as' => "update-url-rule",
            'uses' => 'UrlController@updateRule'
        ]);

    Route::post('/delete-url-rule', [
            'middleware' => 'auth',
            'as' => "delete-url-rule",
            'uses' => 'UrlController@deleteRule'
        ]);

    Route::get('/get-custom-dataTable', [
            'middleware' => 'auth',
            'as' => "get-custom-dataTable",
            'uses' => 'CustomController@getDataTable'
        ]);

    Route::post('/add-custom-rule', [
            'middleware' => 'auth',
            'as' => "add-custom-rule",
            'uses' => 'CustomController@addRule'
        ]);

    Route::post('/update-custom-rule', [
            'middleware' => 'auth',
            'as' => "update-custom-rule",
            'uses' => 'CustomController@updateRule'
        ]);

    Route::post('/delete-custom-rule', [
            'middleware' => 'auth',
            'as' => "delete-custom-rule",
            'uses' => 'CustomController@deleteRule'
        ]);

    }
);
