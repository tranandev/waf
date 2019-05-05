<?php
$namespace = 'App\Modules\Security\Controllers';
Route::group(
    ['module'=>'Security', 'namespace' => $namespace, 'middleware' => 'web'],
    function() {
	Route::get('/security', [
            'middleware' => 'auth',
            'as' => "security",
            'uses' => 'SecurityController@index'
        ]);

	Route::get('/check', [
            'middleware' => 'auth',
            'as' => "check",
            'uses' => 'SecurityController@checkSecure'
        ]);

	Route::post('/fix', [
            'middleware' => 'auth',
            'as' => "fix",
            'uses' => 'SecurityController@fixError'
        ]);

	Route::get('/fix-all', [
            'middleware' => 'auth',
            'as' => "fix-all",
            'uses' => 'SecurityController@fixErrorAll'
        ]);

    }
);
