<?php

namespace App\Http\Middleware;

use App\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSecurityAccess
{
    public function handle(
        Request $request,
        Closure $next,
    ): Response {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->isActive()) {
            abort(403, 'Akun Anda tidak aktif.');
        }

        if (! in_array($user->role, [
            UserRole::ADMIN,
            UserRole::SECURITY,
        ], true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman Security.');
        }

        return $next($request);
    }
}