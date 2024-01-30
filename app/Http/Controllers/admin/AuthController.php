<?php

namespace App\Http\Controllers\admin;

use DB;
use App\Models\Siswa;
use App\Models\Pembina;
use App\Models\admin\User;
use Illuminate\Support\Str;
use App\Models\ProfileSiswa;
use Illuminate\Http\Request;
use App\Models\ProfilePembina;
use App\Models\admin\UserGroup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(){
        if (auth()->guard('admin')->check()) {
            return redirect()->route('admin.dashboard')->with('warning', 'Kamu sudah login');
        }
        
        return view('administrator.authentication.login');
    }

    public function checkEmail(Request $request){
        if($request->ajax()){
            $userExists = User::where('email', $request->email)->first();
            $pembinaExists = Pembina::where('email', $request->email)->first();
            $siswaExists = Siswa::where('email', $request->email)->first();
    
            if(empty($userExists) && empty($pembinaExists) && empty($siswaExists)){
                return response()->json([
                    'message' => 'Email tidak terdaftar',
                    'valid' => false
                ]);
            } else {
                return response()->json([
                    'valid' => true
                ]);
            }
        }
    }
    
    public function checkPassword(Request $request) {
        if ($request->ajax()) {
            $user = User::where('email', $request->email)->first();
            $pembina = Pembina::where('email', $request->email)->first();
            $siswa = Siswa::where('email', $request->email)->first();
    
            // Check in User model
            if ($user && Hash::check($request->password, $user->password)) {
                return response()->json([
                    'valid' => true
                ]);
            }
    
            // Check in Pembina model
            if ($pembina && Hash::check($request->password, $pembina->password)) {
                return response()->json([
                    'valid' => true
                ]);
            }
    
            // Check in Siswa model
            if ($siswa && Hash::check($request->password, $siswa->password)) {
                return response()->json([
                    'valid' => true
                ]);
            }
    
            // If no match is found in any model
            return response()->json([
                'message' => 'Email atau password tidak sesuai',
                'valid' => false
            ]);
        }
    }
    
    
    public function loginProses(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('admin.dashboard')->with('success', 'Berhasil login');
        } else if (Auth::guard('siswa')->attempt($credentials)) {
            return redirect()->route('siswa.dashboard')->with('success', 'Berhasil login');
        } else if (Auth::guard('pembina')->attempt($credentials)) {
            return redirect()->route('pembina.dashboard')->with('success', 'Berhasil login');
        }

        // Jika autentikasi gagal, alihkan kembali ke halaman masuk dengan pesan error
        return redirect()->route('admin.login')->with('error', 'Email atau password salah.');
    }

    public function logout()
    {
        if (auth()->user() || auth()->guard('siswa')->user() || auth()->guard('pembina')->user()) {
            if (auth()->user()) {
                Auth::logout();
                return redirect()->route('admin.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
            } else if (auth()->guard('siswa')->user()) {
                Auth::guard('siswa')->logout();
                return redirect()->route('admin.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
            } else if (auth()->guard('pembina')->user()) {
                Auth::guard('pembina')->logout();
                return redirect()->route('admin.login')->with('success', 'Berhasil Logout.'); // Ganti 'login' dengan rute halaman masuk yang sesuai
            }
        }
    }

    public function registrasi(){
        return view('administrator.authentication.registrasi.index');
    }

    public function registrasi_siswa(){
        return view('administrator.authentication.registrasi.siswa');
    }

    public function registrasi_siswa_save(Request $request){
        $request->validate([
            'eskul' => 'required',
            'name' => 'required',
            'email' => 'required|unique:siswa',
            'password' => 'required|min:8',
            'konfirmasi_password' => 'required|min:8|same:password',
            'telepon' => 'required',
            'nis' => 'required',
        ]);

        $user_group = UserGroup::where('name', 'Siswa')->first();
        
        try {
            DB::beginTransaction();
    
            if (!$user_group) {
                $user_group = UserGroup::create([
                    'name' => 'Siswa',
                    'status' => 1
                ]);
            }
            
            $data = Siswa::create([
                'eskul_id' => $request->eskul,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_group_id' => $user_group->id,
                'status' => 1,
                'confirm' => 0,
                'kode' => 'siswa-' . substr(uniqid(), -5),
                'nis' => $request->nis,
                'no_telepon' => $request->telepon,
                'remember_token' => Str::random(60),
                'uuid' => Str::uuid(),
            ]);
    
            $profile = ProfileSiswa::create([
                'siswa_kode' => $data['kode'],
                'sosial_media' => '{
                    "linkedin": "",
                    "twitter": "",
                    "instagram": "",
                    "facebook": ""
                  }',
            ]);
        
            DB::commit();
            return redirect()->route('admin.login')->with('success', 'Berhasil membuat akun, tunggu email dari kami atau hubungi admin.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.registrasi.siswa')->with('error', $th->getMessage());
        }
    }

    public function registrasi_siswa_checkEmail(Request $request){
        if($request->ajax()){
            // Check email in Siswa table
            $pembinaExists = Pembina::where('email', $request->email)->withTrashed();
    
            if(isset($request->id)){
                $pembinaExists->where('id', '!=', $request->id);
            }
            
            $siswaExists = Siswa::where('email', $request->email)->withTrashed();
    
            if(isset($request->id)){
                $siswaExists->where('id', '!=', $request->id);
            }
    
            // Check email in User table
            $userExists = User::where('email', $request->email);
    
            if(isset($request->id)){
                $userExists->where('id', '!=', $request->id);
            }
    
            // Combine the results
            $emailExists = $pembinaExists->exists() || $userExists->exists() || $siswaExists->exists();
    
            if($emailExists){
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
    
    public function registrasi_siswa_checkNis(Request $request){
        if($request->ajax()){
            $users = Siswa::where('nis', $request->nis)->withTrashed();
            
            if(isset($request->id)){
                $users->where('id', '!=', $request->id);
            }
    
            if($users->exists()){
                return response()->json([
                    'message' => 'Nis sudah dipakai',
                    'valid' => false
                ]);
            } else {
                return response()->json([
                    'valid' => true
                ]);
            }
        }
    }
    
    public function registrasi_siswa_checkTelepon(Request $request){
        if($request->ajax()){
            $users = Siswa::where('no_telepon', $request->telepon)->withTrashed();
            
            if(isset($request->id)){
                $users->where('id', '!=', $request->id);
            }
    
            if($users->exists()){
                return response()->json([
                    'message' => 'Telepon sudah dipakai',
                    'valid' => false
                ]);
            } else {
                return response()->json([
                    'valid' => true
                ]);
            }
        }
    }
    
    public function registrasi_pembina(){
        return view('administrator.authentication.registrasi.pembina');
    }

    public function registrasi_pembina_save(Request $request){
        $request->validate([
            'eskul' => 'required',
            'name' => 'required',
            'email' => 'required|unique:pembina',
            'password' => 'required|min:8',
            'konfirmasi_password' => 'required|min:8|same:password',
            'telepon' => 'required',
        ]);

        $user_group = UserGroup::where('name', 'Pembina')->first();
        
        try {
            DB::beginTransaction();
    
            if (!$user_group) {
                $user_group = UserGroup::create([
                    'name' => 'Pembina',
                    'status' => 1
                ]);
            }
            
            $data = Pembina::create([
                'eskul_id' => $request->eskul,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_group_id' => $user_group->id,
                'status' => 1,
                'confirm' => 0,
                'kode' => 'pembina-' . substr(uniqid(), -5),
                'no_telepon' => $request->telepon,
                'remember_token' => Str::random(60),
                'uuid' => Str::uuid(),
            ]);
    
            $profile = ProfilePembina::create([
                'pembina_kode' => $data['kode'],
                'sosial_media' => '{
                    "linkedin": "",
                    "twitter": "",
                    "instagram": "",
                    "facebook": ""
                  }',
            ]);
        
            DB::commit();
            return redirect()->route('admin.login')->with('success', 'Berhasil membuat akun, tunggu email dari kami atau hubungi admin.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.registrasi.pembina')->with('error', $th->getMessage());
        }
    }

    public function registrasi_pembina_checkEmail(Request $request){
        if($request->ajax()){
            // Check email in Siswa table
            $pembinaExists = Pembina::where('email', $request->email)->withTrashed();
    
            if(isset($request->id)){
                $pembinaExists->where('id', '!=', $request->id);
            }
            
            $siswaExists = Siswa::where('email', $request->email)->withTrashed();
    
            if(isset($request->id)){
                $siswaExists->where('id', '!=', $request->id);
            }
    
            // Check email in User table
            $userExists = User::where('email', $request->email);
    
            if(isset($request->id)){
                $userExists->where('id', '!=', $request->id);
            }
    
            // Combine the results
            $emailExists = $pembinaExists->exists() || $userExists->exists() || $siswaExists->exists();
    
            if($emailExists){
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
    
    public function registrasi_pembina_checkTelepon(Request $request){
        if($request->ajax()){
            $users = Pembina::where('no_telepon', $request->telepon)->withTrashed();
            
            if(isset($request->id)){
                $users->where('id', '!=', $request->id);
            }
    
            if($users->exists()){
                return response()->json([
                    'message' => 'Telepon sudah dipakai',
                    'valid' => false
                ]);
            } else {
                return response()->json([
                    'valid' => true
                ]);
            }
        }
    }
}
