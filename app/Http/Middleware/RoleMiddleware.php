<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Session::has('admin_id')) {
            return redirect()->route('admin.login');
        }

        $userRole = Session::get('admin_role');

        if (!in_array($userRole, $roles)) {
            // Unauthorized
            if ($userRole == 'penyuluh') {
                return redirect()->route('admin.konsultasi.index');
            }
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
