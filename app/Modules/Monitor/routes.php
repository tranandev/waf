<?php
$namespace = 'App\Modules\Monitor\Controllers';
Route::group(
    ['module'=>'Monitor', 'namespace' => $namespace, 'middleware' => 'web'],
    function() {

    Route::get('/monitor', [
        'middleware' => 'auth',
        'as' => "monitor",
        'uses' => 'MonitorController@index'
    ]);  

	Route::get('/get-monitor-dataTable', [
            'middleware' => 'auth',
            'as' => "get-monitor-dataTable",
            'uses' => 'MonitorController@getMonitorDataTable'
    ]);

    Route::get('/get-10-ip-waf', [
            'middleware' => 'auth',
            'as' => "get-ip",
            'uses' => 'MonitorController@getIP'
    ]);

    Route::get('/get-10-at-waf', [
            'middleware' => 'auth',
            'as' => "get-attack",
            'uses' => 'MonitorController@getAttack'
    ]);

    }
);
