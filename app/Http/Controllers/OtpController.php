<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use App\Mail\OtpMail;
use App\Models\Siswa;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        // Validasi input
        $request->validate([
            'channel' => 'required', // Sesuaikan dengan format nomor telepon Anda
            'uuid' => 'required', // Sesuaikan dengan format nomor telepon Anda
        ]);

        // Temukan pengguna berdasarkan nomor telepon
        $user = Siswa::where('uuid', $request->uuid)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Generate OTP
        $otpCode = rand(1000, 9999);

        // Kirim OTP melalui SMS menggunakan Twilio
        $phoneNumber = '+62' . substr($user->no_telepon, 1);
        if ($request->channel === 'mail') {
            $this->sendMail($user, $otpCode);
            $channel = 'Mail';
        }else if($request->channel === 'sms'){
            $this->sendSms($phoneNumber, $otpCode);
            $channel = 'SMS';
        }else if($request->channel === 'whatsapp'){
            $this->sendWhatsapp($phoneNumber, $otpCode);
            $channel = 'Whatsapp';
        }

        // Simpan OTP ke dalam database
        $hasOtp = OTP::where('uuid', $request->uuid)->get();
        if (!empty($hasOtp)) {
            foreach ($hasOtp as $row) {
                $row->delete();
            }
        }

        $otp = OTP::create([
            'uuid' => $request->uuid,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes(5), // Sesuaikan dengan kebutuhan Anda
        ]);

        return response()->json(['message' => 'OTP sent successfully. Please check your ' . $channel]);
    }

    private function sendSms($phoneNumber, $otpCode)
    {
        $twilioAccountSid = config('services.twilio.account_sid');
        $twilioAuthToken = config('services.twilio.auth_token');
        $twilioPhoneNumber = config('services.twilio.phone_number');
        

        $twilio = new Client($twilioAccountSid, $twilioAuthToken);

        $verification = $twilio->verify->v2->services("VA796701715717bfde50e8f2dd40838a3c")
                                ->verifications
                                ->create($phoneNumber, "sms"
                                );

                                
        $twilio->verify->v2->services("VA796701715717bfde50e8f2dd40838a3c")
        ->verifications($verification->sid)
        ->update("approved");
    }

    private function sendWhatsapp($phoneNumber, $otpCode)
    {
        $twilioAccountSid = config('services.twilio.account_sid');
        $twilioAuthToken = config('services.twilio.auth_token');
        $twilioPhoneNumber = config('services.twilio.phone_number');
        

        $twilio = new Client($twilioAccountSid, $twilioAuthToken);

        $verification = $twilio->verify->v2->services("VA796701715717bfde50e8f2dd40838a3c")
                                ->verifications
                                ->create($phoneNumber, "whatsapp"
                                );

                                
        $twilio->verify->v2->services("VA796701715717bfde50e8f2dd40838a3c")
        ->verifications($verification->sid)
        ->update("approved");
    }

    private function sendMail($user, $otpCode)
    {
        $mailData = [
            'title' => 'OTP for verify account',
            'email' => $user->email,
            'uuid' => $user->uuid,
            'code' => $otpCode,
            'username' => $user->name ?? 'User',
        ];
        Mail::to($user->email)->send(new OtpMail($mailData));
    }
}
