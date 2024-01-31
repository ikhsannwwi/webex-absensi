<?php

namespace App\Http\Controllers\admin;

use DB;
use File;
use DataTables;
use App\Models\Siswa;
use App\Models\Pembina;
use App\Models\admin\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\admin\Profile;
use App\Models\ResetPassword;
use App\Mail\ResetPasswordMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    private static $module = "profile";

    public function index($kode) {
        //Check permission
        if (auth()->user()->kode != 'daysf' && $kode != auth()->user()->kode) {
            abort(403);
        }
        
        // Temukan data pengguna berdasarkan kode
        // Temukan data pengguna berdasarkan kode
        $data = Profile::with('user')
        ->where('user_kode',$kode)
        ->first();
        if (!$data) {
            # code...

            $sosmedData = [
                'linkedin' => '',
                'twitter' => '',
                'instagram' => '',
                'facebook' => '',
            ];
            $sosmedJson = json_encode($sosmedData);
            $profile = Profile::create([
                'user_kode' => auth()->user() ? auth()->user()->kode : '',
                'sosial_media' => $sosmedJson,
            ]);

            $profile->save();
            $sosmed = json_decode($sosmedJson, true); // Mengubah JSON menjadi array
        }else{
            $sosmed = json_decode($data->sosial_media, true); // Mengubah JSON menjadi array
        }
        // dd($sosmed);
        // Jika data tidak ditemukan, tampilkan pesan kesalahan atau arahkan ke halaman lain
        if (!$data) {
            return redirect()->route('admin.dashboard')->with('error', 'Data pengguna tidak ditemukan.');
        }
    
        return view('administrator.profile.index', compact('data','sosmed'));
    }
    

    public function getData(Request $request){
        $data = Profile::with('user')->get();

        return DataTables::of($data)
            ->make(true);
    }
    
    public function update(Request $request)
    {
        $kode = $request->kode;

        // Check permission
        if ($kode != auth()->user()->kode) {
            abort(403);
        }

        $data = Profile::where('user_kode',$kode)->with('user')->first();

        if (!$data) {
            return redirect()->route('admin.profile',$kode)->with('error', 'Data tidak ditemukan.');
        }

        $request->validate([
            'email' => 'unique:users,email,' . $data->user->id,
        ]);

        // Simpan data sebelum diupdate
        $previousData = $data->toArray();

        $updates = [];

        if ($request->filled('full_name')) {
            $updates['full_name'] = $request->full_name;
        }
        if ($request->filled('no_telepon')) {
            $updates['no_telepon'] = $request->no_telepon;
        }
        if ($request->filled('pendidikan_terakhir')) {
            $updates['pendidikan_terakhir'] = $request->pendidikan_terakhir;
        }
        if ($request->filled('tempat_lahir')) {
            $updates['tempat_lahir'] = $request->tempat_lahir;
        }
        if ($request->filled('tanggal_lahir')) {
            $updates['tanggal_lahir'] = $request->tanggal_lahir;
        }
        if ($request->filled('alamat')) {
            $updates['alamat'] = $request->alamat;
        }
        if ($request->filled('sosmed_linkedin') || $request->filled('sosial_media') || $request->filled('sosial_media') || $request->filled('sosial_media')) {
            $sosmedData = [
                'linkedin' => $request->sosmed_linkedin,
                'twitter' => $request->sosmed_twitter,
                'instagram' => $request->sosmed_instagram,
                'facebook' => $request->sosmed_facebook,
            ];
            $sosmedJson = json_encode($sosmedData);

            $updates['sosial_media'] = $sosmedJson;
        }
        if ($request->hasFile('foto_user_profile')) {

            if (!empty($data->foto)) {
                $image_path = "./administrator/assets/media/profile/" . $data->foto;
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }

            $image = $request->file('foto_user_profile');
            $fileName = 'foto-profile_' . $data->user->name . '_' . date('Y-m-d-H-i-s') . '_' . uniqid(2) . '.' . $image->getClientOriginalExtension();
            $path = upload_path('profile') . $fileName;
            Image::make($image->getRealPath())->save($path, 100);
            $updates['foto'] = $fileName;
        }
        
        if ($request->filled('email')) {
            $user = User::where('kode', $kode)->first();
            if ($user) {
                $user->update(['email' => $request->email]);
            } else {
                return redirect()->route('admin.profile',$kode)->with('error', 'User tidak ditemukan.');
            }
        }

        $data->update($updates);

        // Kumpulkan data yang diperbarui dalam array
        $updatedData = [];
        foreach ($updates as $key => $value) {
            $updatedData[$key] = $data->$key;
        }

        // Kirim data yang diperbarui ke fungsi createLog
        createLog(static::$module, __FUNCTION__, $kode, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => ['data' => $updatedData, 'user' => $user]]);

        return redirect()->route('admin.profile',$kode)->with('success', 'Data berhasil diupdate.');
    }




    
    public function getDetail($kode){

        $data = Profile::with('user')->find($kode);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail user.',
        ]);
    }

    public function checkEmail(Request $request){
        if($request->ajax()){
            $users = User::where('email', $request->email);
            
            if(isset($request->kode)){
                $users->where('kode', '!=', $request->kode);
            }

            $users->get();
    
            if($users->exists()){
                return response()->json([
                    'message' => 'Email sudah dipakai',
                    'valid' => false
                ]);
            } else {
                return response()->json([
                    'valid' => true
                ]);
            }
        }
    }

    
    public function request(){
        return view('administrator.profile.reset_password.index');
    }

    public function email(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => now()]
        );

        $user_main = User::where('email', $email)->first();
        $siswa = Siswa::where('email', $email)->first();
        $pembina = Pembina::where('email', $email)->first();

        $user = $user_main ?? $siswa ?? $pembina;

        if ($user) {
            $mailData = [
                'title' => 'Reset Password',
                'email' => $email,
                'token' => $token,
                'username' => $user->name ?? 'User',
                'resetLink' => route('admin.profile.password.reset', $token),
            ];
            Mail::to($email)->send(new ResetPasswordMail($mailData));
            return redirect(route('admin.profile.password.request'))->with('success', 'Tautan berhasil dikirim melalui email');
        }else{
            return redirect(route('admin.profile.password.request'))->with('error', 'Gagal');
        }
    }

    public function resetPassword($token){
        $resetPassword = ResetPassword::where('token', $token)->first();

        if (!$resetPassword) {
            abort(404);
        }

        return view('administrator.profile.reset_password.reset', compact('resetPassword'));
    }

    public function updatePassword(Request $request, $token){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'konfirmasi_password' => 'required|min:8|same:password',
        ]);
    
        $resetPassword = ResetPassword::where('token', $token)
                                  ->where('email', $request->input('email'))
                                  ->first();
    
        if (!$resetPassword) {
            return redirect(route('admin.profile.password.reset', $token))->with('error', 'Email tidak sesuai');
        }
        
        // Ambil user dari berbagai model
        $user_main = User::where('email', $request->email)->first();
        $siswa = Siswa::where('email', $request->email)->first();
        $pembina = Pembina::where('email', $request->email)->first();
    
        // Pilih model yang ditemukan pertama
        $user = $user_main ?? $siswa ?? $pembina;
    
        // Pastikan $user bukan null dan merupakan instance dari model yang sesuai
        if ($user instanceof User || $user instanceof Siswa || $user instanceof Pembina) {
            $user->update([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ]);
    
            // Hapus token dari tabel reset password
            ResetPassword::where('token', $token)
                ->where('email', $request->input('email'))
                ->delete();
    
            return redirect()->route('admin.login')->with('success', 'Password has been reset successfully.');
        } else {
            // Handle case when no user is found or when the user is not an instance of expected models.
            return redirect(route('admin.profile.password.reset', $token))->with('error', 'User tidak ditemukan');
        }
    }
    
}
