<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChekUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect('/login');
        }

        $id_user = Auth::id();
        $user = User::with('role')->find($id_user);

        if (!$user) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid session. Please log in again.'], 401);
            }

            return redirect()->route('login')->with('error', 'Sesi Anda tidak valid. Silakan login kembali.');
        }

        $userRoleName = $user->role ? $user->role->nama_role : null;

        if ($userRoleName && in_array($userRoleName, $roles)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden. You don\'t have access to this resource.'], 403);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }


}
