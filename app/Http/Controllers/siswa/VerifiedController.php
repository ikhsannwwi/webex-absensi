<?php

namespace App\Http\Controllers\siswa;

use DB;
use App\Models\OTP;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerifiedController extends Controller
{
    public function index($uuid){
        $uuid = $uuid;
        return view('siswa.authentication.verified', compact('uuid'));
    }

    public function verify(Request $request, $uuid){
        $otp = OTP::where('uuid', $uuid)->first();

        if ($otp && $otp->otp_code !== $request->code_otp) {
            return redirect()->route('siswa.verified', $uuid)->with('error', 'Invalid OTP');
        }
    
        if ($otp && $otp->expires_at > now()) {
            $user = Siswa::where('uuid', $uuid)->first();
    
            if (!empty($user)) {
                try {
                    DB::beginTransaction();
                    $user->update(['email_verified_at' => now()]);
                    $otp->delete();
                    DB::commit();
                    return redirect()->route('siswa.dashboard')->with('success', 'Akunmu berhasil diverifikasi');
                } catch (\Throwable $th) {
                    DB::rollback();
                    return redirect()->route('siswa.verified', $uuid)->with('error', $th->getMessage());
                }
            }
        } else {
            return redirect()->route('siswa.verified', $uuid)->with('error', 'Expired OTP');
        }
    
        // Handle the case where OTP is not found or has expired
        return redirect()->route('siswa.verified', $uuid)->with('error', 'Invalid or expired OTP');
    }
}
