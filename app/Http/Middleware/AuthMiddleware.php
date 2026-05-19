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

        // Admin autenticado tentando acessar rota de franqueado sem ter feito autoLogin:
        // redireciona para o dashboard admin ao invés da tela de login do franqueado.
        if ($this->guard === 'franqueados' && Auth::guard('admins')->check()) {
            return redirect()->route('admin.index');
        }

        return redirect($this->url . '/login')->with('error', "Vocẽ não está logado.");
    }
}
