<?php
$namespace = 'App\Modules\Waf\Controllers';
Route::group(
    ['module'=>'Waf', 'namespace' => $namespace, 'middleware' => 'web'],
    function() {
	Route::get('/waf', [
            'middleware' => 'auth',
            'as' => "waf",
            'uses' => 'WafController@index'
        ]);

	Route::get('/get-group-rule', [
            'middleware' => 'auth',
            'as' => "get-group-rule",
            'uses' => 'WafController@getGroupRule'
        ]);

    Route::post('/change-group-website-status', [
            'middleware' => 'auth',
            'as' => "change-group-website-status",
            'uses' => 'WafController@changeGroupWebsiteStatus'
        ]);

    Route::post('/change-rule-status', [
            'middleware' => 'auth',
            'as' => "change-rule-status",
            'uses' => 'WafController@changeRuleStatus'
        ]);

    Route::get('/restart', [
            'middleware' => 'auth',
            'as' => "restart",
            'uses' => 'WafController@Restart'
        ]);

    }
);
