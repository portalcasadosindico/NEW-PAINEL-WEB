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
        $dbg = function($msg) { file_put_contents(storage_path('logs/login_debug.txt'), date('H:i:s') . " $msg\n", FILE_APPEND); };
        $dbg('login() called, email=' . $request->email . ' guard=' . $this->guard);
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $dbg('password len=' . strlen($request->password));
        $provider = Auth::guard($this->guard)->getProvider();
        $userFound = $provider->retrieveByCredentials(['email' => $request->email]);
        $dbg('user found: ' . ($userFound ? 'yes id='.$userFound->id.' senhaLen='.strlen($userFound->senha??'') : 'NO'));
        if ($userFound) {
            $dbg('validateCredentials: ' . ($provider->validateCredentials($userFound, $credentials) ? 'true' : 'false'));
        }
        $dbg('calling attempt()');
        $result = Auth::guard($this->guard)->attempt($credentials, $request->boolean('remember'));
        $dbg('attempt() returned: ' . ($result ? 'true' : 'false'));
        if ($result) {
            $dbg('redirecting to ' . $this->url . '.index');
            return redirect()->route($this->url . '.index');
        } else {
            $dbg('returning back with errors');
            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Credenciais inválidas.']);
        }
    }
    public function showRegisterForm()
    {
        return view($this->url . '.auth.register.register');
    }
    public function logout()
    {
        Auth::guard($this->guard)->logout();
        return redirect()->route('admin.login');
    }

    public function autoLogin(Request $request)
    {
        $dbg = function($msg) { file_put_contents(storage_path('logs/autologin_debug.txt'), date('H:i:s') . " $msg\n", FILE_APPEND); };
        $dbg('autoLogin start, franqueado_id=' . $request->franqueado_id . ', guard=' . $this->guard);
        $result = Auth::guard($this->guard)->loginUsingId($request->franqueado_id);
        $dbg('loginUsingId result: ' . ($result ? 'true' : 'false'));
        if ($result) {
            $dbg('before session_start');
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            $dbg('after session_start');
            $_SESSION['login_as_admin'] = true;
            $dbg('before redirect');
            return redirect()->route($this->url . '.index');
        }
        $dbg('loginUsingId returned false — franqueado not found?');
    }

    public function autoLogout()
    {
        if (Auth::guard('franqueados')->check()) {
            Auth::guard('franqueados')->logout();
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            $_SESSION['login_as_admin'] = true;
            return redirect()->route('admin.index');
        } else {
            return redirect()->back();
        }
    }
}
