<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guard('admin')->check()) {
            $user = auth()->guard('admin')->user();

            // Check if the admin user's status is 1
            if ($user->status == 1) {
                // If the status is 1, allow access to the requested route
                return $next($request);
            } else {
                // If the status is not 1, log out the user and redirect to the login page
                auth()->guard('admin')->logout();
                $request->session()->invalidate();
                return redirect()->route('admin.login')->with('info', 'Akunmu sudah tidak aktif.');
            }
        } else if (auth()->guard('siswa')->check()) {
            $user = auth()->guard('siswa')->user();
            if ($user->status == 1) {
                if ($user->confirm == 1) {
                    if ($user->email_verified_at != null) {
                        auth()->guard('siswa')->login($user);
                        return $next($request);
                    } else {
                        return redirect()->route('siswa.verified', $user->uuid)->with('info', 'Akunmu belum terverifikasi.');
                    }
                } else {
                    auth()->guard('siswa')->logout();
                    $request->session()->invalidate();
                    return redirect()->route('admin.login')->with('info', 'Akunmu belum diacc. Konfirmasi ke admin ekstrakurikulermu');
                }
            } else {
                auth()->guard('siswa')->logout();
                $request->session()->invalidate();
                return redirect()->route('admin.login')->with('info', 'Akunmu sudah tidak aktif.');
            }
        } else if (auth()->guard('pembina')->check()) {
            $user = auth()->guard('pembina')->user();
            if ($user->status == 1) {
                if ($user->confirm == 1) {
                    auth()->guard('pembina')->login($user);
                    return $next($request);
                } else {
                    auth()->guard('pembina')->logout();
                    $request->session()->invalidate();
                    return redirect()->route('admin.login')->with('info', 'Akunmu belum diacc. Konfirmasi ke admin ekstrakurikulermu');
                }
            } else {
                auth()->guard('pembina')->logout();
                $request->session()->invalidate();
                return redirect()->route('admin.login')->with('info', 'Akunmu sudah tidak aktif.');
            }
        }

        // If the user is not authenticated as an admin, redirect to the login page
        return redirect()->route('admin.login')->with('warning', 'Kamu belum login.');
    }
}
