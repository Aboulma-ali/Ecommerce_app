<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // On vérifie si l'utilisateur est connecté ET s'il a le rôle 'Admin'
        if ($request->user() && $request->user()->hasRole('Admin')) {
            // Si c'est le cas, on le laisse continuer sa requête
            return $next($request);
        }

        // Sinon, on le renvoie vers une erreur 403 (Accès Interdit)
        abort(403, 'Accès non autorisé.');
    }
}
