<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $user;
    private $user_franqueado;
    protected $url = '';
    protected $guard = '';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->url = '';
        if ($request->is('admin_franqueado/*') || $request->is('admin_franqueado')) {
            $this->url = 'admin_franqueado';
            $this->guard = 'franqueados';
        } else if ($request->is('admin/*') || $request->is('admin')) {
            $this->url = 'admin';
            $this->guard = 'admins';
        }
    }

    public function __get($param)
    {
        if ($param == "user") {
            return Auth::guard('admins')->user();
        } elseif ($param == "user_franqueado") {
            return Auth::guard('franqueados')->user();
        } else {
            return $this->$param;
        }
    }
}
