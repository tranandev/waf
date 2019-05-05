<?php
$namespace = 'App\Modules\GroupRules\Controllers';
Route::group(
    ['module'=>'GroupRules', 'namespace' => $namespace, 'middleware' => 'web'],
    function() {
	Route::get('/group-rules', [
            'middleware' => 'auth',
            'as' => "group-rules",
            'uses' => 'GroupRulesController@index'
        ]);

	Route::get('/get-rule-dataTable', [
            'middleware' => 'auth',
            'as' => "get-rule-dataTable",
            'uses' => 'GroupRulesController@getDataTable'
        ]);

	Route::post('/add-group-rule', [
            'middleware' => 'auth',
            'as' => "add-group-rule",
            'uses' => 'GroupRulesController@addGroupRule'
        ]);

	Route::post('/update-group-rule', [
            'middleware' => 'auth',
            'as' => "update-group-rule",
            'uses' => 'GroupRulesController@updateGroupRule'
        ]);

	Route::post('/delete-group-rule', [
            'middleware' => 'auth',
            'as' => "delete-group-rule",
            'uses' => 'GroupRulesController@deleteGroupRule'
        ]);

    }
);
