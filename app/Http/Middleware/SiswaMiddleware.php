<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiswaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated as an siswa
        if (auth()->guard('siswa')->check()) {
            $user = auth()->guard('siswa')->user();

            // Check if the siswa user's status is 1
            if ($user->status == 1) {
                // If the status is 1, allow access to the requested route
                return $next($request);
            } else {
                // If the status is not 1, log out the user and redirect to the login page
                auth()->guard('siswa')->logout();
                $request->session()->invalidate();
                return redirect()->route('siswa.login')->with('info', 'Akunmu sudah tidak aktif.');
            }
        }

        // If the user is not authenticated as an siswa, redirect to the login page
        return redirect()->route('siswa.login')->with('warning', 'Kamu belum login.');
    }
}
