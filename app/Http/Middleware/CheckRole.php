<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;
class CheckRole {
    public function handle(Request $request, Closure $next, string ...$roles): mixed {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        if (!$user->is_active) { Auth::logout(); return redirect()->route('login')->withErrors(['email'=>'Akun tidak aktif.']); }
        if ($user->role === 'admin' || in_array($user->role, $roles)) return $next($request);
        abort(403, 'Akses ditolak.');
    }
}