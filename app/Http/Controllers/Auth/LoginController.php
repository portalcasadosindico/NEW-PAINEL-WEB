<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function loginPage()
    {
        return view($this->url . '.auth.login');
    }
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (Auth::guard($this->guard)->attempt($credentials)) {
            return redirect()->route($this->url . '.index');
        } else {
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request.']);
        }
    }
    public function showRegisterForm()
    {
        return view($this->url . '.auth.register.register');
    }
    public function logout()
    {
        Auth::guard($this->guard)->logout();
        return redirect()->route($this->url . '.index');
    }

    public function autoLogin(Request $request)
    {
        if (Auth::guard($this->guard)->loginUsingId($request->franqueado_id)) {
            session_start();
            $_SESSION['login_as_admin'] = true;
            return redirect()->route($this->url . '.index');
        }
    }

    public function autoLogout()
    {
        if (Auth::guard('franqueados')->check()) {
            Auth::guard('franqueados')->logout();
            session_start();
            $_SESSION['login_as_admin'] = true;
            return redirect()->route('admin.index');
        } else {
            return redirect()->back();
        }
    }
}
