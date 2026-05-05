<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->url = '';
        if ($request->is('admin_franqueado/*') || $request->is('admin_franqueado')) {
            $this->url = 'admin_franqueado';
            $this->guard = 'franqueados';
        } else if ($request->is('admin/*') || $request->is('admin')) {
            $this->url = 'admin';
            $this->guard = 'admins';
        }
        if (Auth::guard($this->guard)->check()) {
            return $next($request);
        }
        return redirect($this->url . '/login')->with('error', "Vocẽ não está logado.");
    }
}
