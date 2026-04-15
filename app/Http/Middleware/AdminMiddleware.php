<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar se o usuário está logado e é admin
        // Você pode adicionar um campo 'is_admin' na tabela users
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Acesso não autorizado. Área restrita para administradores.');
        }
        
        return $next($request);
    }
}