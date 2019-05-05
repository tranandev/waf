<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
//use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use URL;
//use Input;
//use Validator;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $validationRule = array(
        'username' => 'required',
        'password' => 'required'
    );
    /**
     * Create a new controller instance.
     *
     * @return void
     */
public function __construct()
    {
         $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
    }



    public function getLogin() {
        return view('auth.login');
    }



    public function postLogin() {
    	$validator = Validator::make(Input::all(), $this->validationRule);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $username = Input::get('username');
        $password = Input::get('password');
        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            return redirect('/');
        } else {
            return redirect()->back()->withInput()->with('message', 'Login Failed');
        }
    }

    public function getLogout() {
        Auth::logout();
        return redirect('/');
    }

}
