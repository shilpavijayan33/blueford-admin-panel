<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Input;
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
    protected $redirectTo = 'admin/product-management';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(\Illuminate\Http\Request $request)
    {
       $remember = (Input::has('remember')) ? true : false;  
       $usr= $request->email;
       $request->validate([
            'email' => 'required|email|exists:users',
        ]);  
       $cur_user = User::where('email','=',$usr)->first();
      if($cur_user->status <> 'active' || $cur_user->admin <> 1){
      
        return redirect()->back()->with('error', 'Sorry, You cannot login. Please contact admin!!');
       }
        $this->validateLogin($request,$remember);
        if ($this->hasTooManyLoginAttempts($request,$remember)) {

            $this->fireLockoutEvent($request,$remember);
            return $this->sendLockoutResponse($request,$remember);

        }

        if ($this->attemptLogin($request,$remember)) {
            return $this->sendLoginResponse($request,$remember);
        }

        $this->incrementLoginAttempts($request,$remember);

        return $this->sendFailedLoginResponse($request,$remember);
    }

}
