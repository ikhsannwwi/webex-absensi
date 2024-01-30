<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use App\Models\Siswa;
use App\Models\Pembina;
use App\Models\admin\User;
use Illuminate\Support\Str;
use App\Models\ProfileSiswa;
use Illuminate\Http\Request;
use App\Models\admin\UserGroup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    private static $module = "user";

    public function index(){
        //Check permission
        if (!isAllowed(static::$module, "view")) {
            abort(403);
        }

        return view('administrator.siswa.index');
    }
    
    public function getData(Request $request){
        $data = Siswa::query()->with('user_group');

        if (auth()->user()->email != 'dev@daysf.com') {
            $data->where('email', '!=', 'dev@daysf.com');
        }

        if ($request->status || $request->usergroup) {
            if ($request->status != "") {
                $status = $request->status == "Aktif" ? 1 : 0;
                $data->where("status", $status);
            }
            
            if ($request->usergroup != "") {
                $usergroupid = $request->usergroup ;
                $data->where("user_group_id", $usergroupid);
            }
            $data->get();
        }

        return DataTables::of($data)
            ->addColumn('status', function ($row) {
                if (isAllowed(static::$module, "status")) : //Check permission
                    if ($row->status) {
                        $status = '<div class="d-flex"><div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input h-20px w-30px changeStatus" data-ix="' . $row->id . '" type="checkbox" value="1"
                            name="status" checked="checked" />
                        <label class="form-check-label fw-bold text-gray-400"
                            for="status"></label>
                    </div>';
                        $status .= '<span class="badge bg-success">Aktif</span></div>';
                    } else {
                        $status = '<div class="d-flex"><div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input h-20px w-30px changeStatus" data-ix="' . $row->id . '" type="checkbox" value="1"
                            name="status"/>
                            <label class="form-check-label fw-bold text-gray-400"
                            for="status"></label>
                            </div>';
                        $status .= '<span class="badge bg-danger">Tidak Aktif</span></div>';
                    }
                    return $status;
                endif;
            })
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "edit")) : //Check permission
                    $btn .= '<a href="'.route('admin.siswa.edit',$row->id).'" class="btn btn-primary btn-sm me-3 ">
                    Edit
                </a>';
                endif;
                if (isAllowed(static::$module, "detail")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-secondary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#detailUser">
                    Detail
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    
    public function add(){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        return view('administrator.siswa.add');
    }
    
    public function save(Request $request){
        //Check permission
        if (!isAllowed(static::$module, "add")) {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:siswa,email',
            'password' => 'required|min:8',
            'konfirmasi_password' => 'required|min:8|same:password',
            'user_group' => 'required',
            'status' => 'required',
            'no_telepon' => 'required|unique:siswa,no_telepon',
            'nis' => 'required|unique:siswa,nis',
            'kode' => 'required|unique:siswa,kode',
        ]);

        try {
            DB::beginTransaction();
            $data = Siswa::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_group_id' => $request->user_group,
                'status' => $request->status,
                'confirm' => 1,
                'kode' => $request->kode,
                'nis' => $request->nis,
                'no_telepon' => $request->no_telepon,
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
        
            createLog(static::$module, __FUNCTION__, $data->id, ['Data yang disimpan' => $data]);
            DB::commit();
            return redirect()->route('admin.siswa')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('admin.siswa')->with('error', $th->getMessage());
        }
    }
    
    
    public function edit($id){
        //Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $data = Siswa::find($id);

        if ($data->email == 'dev@daysf.com' && auth()->user()->email != $data->email) {
            return redirect()->route('admin.siswa')->with('warning', 'Forbidden.');
        }

        return view('administrator.siswa.edit',compact('data'));
    }
    
    public function update(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "edit")) {
            abort(403);
        }

        $id = $request->id;
        $data = Siswa::find($id);

        $rules = [
            'name' => 'required',
            'email' => 'required|unique:siswa,email,'.$id,
            'user_group' => 'required',
            'kode' => 'required|unique:siswa,kode,'.$id,
            'no_telepon' => 'required|unique:siswa,no_telepon,'.$id,
            'nis' => 'required|unique:siswa,nis,'.$id,
        ];

        if ($request->password) {
            $rules['password'] = 'required|min:8';
            $rules['konfirmasi_password'] = 'required|min:8|same:password';
        }

        $request->validate($rules);

        // Simpan data sebelum diupdate
        $previousData = $data->toArray();

        $updates = [
            'name' => $request->name,
            'email' => $request->email,
            'user_group_id' => $request->user_group,
            'status' => $request->status,
            'kode' => $request->kode,
            'nis' => $request->nis,
            'no_telepon' => $request->no_telepon,
            'remember_token' => Str::random(60),
        ];

        if ($request->password) {
            $updates['password'] = Hash::make($request->password);
        }

        // Check if a profile exists for the user
        $profile = ProfileSiswa::where('siswa_kode', $data->kode)->firstOrNew([
            'siswa_kode' => $data->kode,
            'sosial_media' => '{"linkedin":"","twitter":"","instagram":"","facebook":""}',
        ]);

        // Update the profile data
        $profile->siswa_kode = $updates['kode'];
        $profile->save();

        // Filter only the updated data
        $updatedData = array_intersect_key($updates, $data->getOriginal());

        $data->update($updates);

        createLog(static::$module, __FUNCTION__, $data->id, ['Data sebelum diupdate' => $previousData, 'Data sesudah diupdate' => $updatedData]);
        return redirect()->route('admin.siswa')->with('success', 'Data berhasil diupdate.');
    }

    
    
    
    public function delete(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "delete")) {
            abort(403);
        }

        // Ensure you have authorization mechanisms here before proceeding to delete data.

        $id = $request->id;

        // Find the user based on the provided ID.
        $user = Siswa::findorfail($id);

        if ($user->email == 'dev@daysf.com' && auth()->user()->email != $user->email) {
            return response()->json([
                'code' => 403,
                'status' => 'forbidden',
                'message' => 'Kamu tidak memiliki akses'
            ], 403);
        }

        if (!$user) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Store the data to be logged before deletion
        $deletedData = $user->toArray();

        // Delete the user.
        $user->delete();

        $profile = ProfileSiswa::where('siswa_kode', $user->kode)->first();

        if ($profile) {
            // Check if the profile is being force-deleted
            $profile->delete();
        }

        // Write logs only for soft delete (not force delete)
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dihapus' => ['User' => $deletedData, 'User Profile' => $profile]]);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Data telah dihapus.',
        ],200);
    }

    
    
    public function getDetail($id){
        //Check permission
        if (!isAllowed(static::$module, "detail")) {
            abort(403);
        }

        $data = Siswa::with('user_group')->with('profile')->find($id);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Sukses memuat detail user.',
        ]);
    }

    public function changeStatus(Request $request)
    {
        //Check permission
        if (!isAllowed(static::$module, "status")) {
            abort(403);
        }
        
        $data['status'] = $request->status == "Aktif" ? 1 : 0;
        $log = $request->status;
        $id = $request->ix;
        $updates = Siswa::where(["id" => $id])->first();
        if (!$updates) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
        // Simpan data sebelum diupdate
        $previousData = $updates->toArray();
        $updates->update($data);

        //Write log
        createLog(static::$module, __FUNCTION__, $id, ['Data User' => $previousData,'Statusnya diubah menjadi' => $log]);
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Status telah diubah ke '. $request->status .'.',
        ], 200);
    }
    
    public function getUserGroup(){
        $usergroup = UserGroup::where('name', 'Siswa')->get();

        return response()->json([
            'usergroup' => $usergroup,
        ]);
    }

    public function getDataUserGroup(){
        $data = UserGroup::where('name', 'Siswa')->get();

        return DataTables::of($data)
            ->make(true);
    }

    public function getDataEskul(){
        // Menggunakan cURL untuk mengambil data dari API
        $ch = curl_init("https://webex.smknegeri1garut.sch.id/api/eskul");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        
        // Mengubah data JSON menjadi array
        $decodedData = json_decode($data, true);
    
        // Pastikan kunci 'data' tersedia dalam respons JSON
        $data = isset($decodedData['data']) ? $decodedData['data'] : [];
    
        // Menggunakan DataTables untuk membuat respons JSON
        return DataTables::of($data)->make(true);
    }
    
    public function generateKode(){
        $generateKode = 'siswa-' . substr(uniqid(), -5);

        return response()->json([
            'generateKode' => $generateKode,
        ]);
    }
    
    public function checkEmail(Request $request){
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
    
    public function checkNis(Request $request){
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
    
    public function checkTelepon(Request $request){
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
    
    public function checkKode(Request $request){
        if($request->ajax()){
            $users = Siswa::where('kode', $request->kode)->withTrashed();
            
            if(isset($request->id)){
                $users->where('id', '!=', $request->id);
            }
    
            if($users->exists()){
                return response()->json([
                    'message' => 'Kode sudah dipakai',
                    'valid' => false
                ]);
            } else {
                return response()->json([
                    'valid' => true
                ]);
            }
        }
    }


    public function arsip(){
        //Check permission
        if (!isAllowed(static::$module, "arsip")) {
            abort(403);
        }

        return view('administrator.siswa.arsip');
    }

    public function getDataArsip(Request $request){
        $data = Siswa::query()->with('user_group')->onlyTrashed();

        if ($request->status || $request->usergroup) {
            if ($request->status != "") {
                $status = $request->status == "Aktif" ? 1 : 0;
                $data->where("status", $status);
            }
            
            if ($request->usergroup != "") {
                $usergroupid = $request->usergroup ;
                $data->where("user_group_id", $usergroupid);
            }
            $data->get();
        }

        return DataTables::of($data)
            ->addColumn('status', function ($row) {
                if (isAllowed(static::$module, "status")) : //Check permission
                    if ($row->status) {
                        $status = '<div class="d-flex"><div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input h-20px w-30px changeStatus" data-ix="' . $row->id . '" type="checkbox" value="1"
                            name="status" checked="checked" />
                        <label class="form-check-label fw-bold text-gray-400"
                            for="status"></label>
                    </div>';
                        $status .= '<span class="badge bg-success">Aktif</span></div>';
                    } else {
                        $status = '<div class="d-flex"><div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input h-20px w-30px changeStatus" data-ix="' . $row->id . '" type="checkbox" value="1"
                            name="status"/>
                            <label class="form-check-label fw-bold text-gray-400"
                            for="status"></label>
                            </div>';
                        $status .= '<span class="badge bg-danger">Tidak Aktif</span></div>';
                    }
                    return $status;
                endif;
            })
            ->addColumn('action', function ($row) {
                $btn = "";
                if (isAllowed(static::$module, "delete")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete me-3 ">
                    Delete
                </a>';
                endif;
                if (isAllowed(static::$module, "restore")) : //Check permission
                    $btn .= '<a href="#" data-id="' . $row->id . '" class="btn btn-primary restore btn-sm me-3 ">
                    Restore
                </a>';
                endif;
                return $btn;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function restore(Request $request)
    {
        // Check permission
        if (!isAllowed(static::$module, "restore")) {
            abort(403);
        }
        
        $id = $request->id;
        $data = Siswa::withTrashed()->find($id);
        $profile = ProfileSiswa::where('siswa_kode', $data->kode)->first();

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        if (!$profile) {
            $profile = ProfileSiswa::create([
                'siswa_kode' => $data->kode,
            ]);
            $userProfiletoarray = '';
        } else {
            # code...
            $userProfiletoarray = "'User Profile' => $profile->toArray()";
        }
        // Simpan data sebelum diupdate
        $previousData = [
            'User' => $data->toArray(),
            $userProfiletoarray
        ];

        $data->restore();

        $updated = ['User' => $data, 'User Profile' => $profile];

        // Write logs if needed.
        createLog(static::$module, __FUNCTION__, $id, ['Data yang dipulihkan' => $updated]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dipulihkan.'
        ]);
    }


    public function forceDelete(Request $request)
    {
        //Check permission
        if (!isAllowed(static::$module, "delete")) {
            abort(403);
        }
        
        $id = $request->id;

        $data = Siswa::withTrashed()->find($id);
        $profile = ProfileSiswa::where('siswa_kode',$data->kode)->first();

        if (!$data) {
            return redirect()->route('admin.siswa.arsip')->with('error', 'Data tidak ditemukan.');
        }

        $data->forceDelete();
        if (!empty($profile)) {
            $profile->delete();
            $dataJsonProfile = $profile;
        } else {
            $dataJsonProfile = '';
        }

        $dataJson = [
            $data,$dataJsonProfile
        ];

        // Write logs if needed.
        createLog(static::$module, __FUNCTION__, $id, $dataJson);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus secara permanent.',
        ]);
    }
}
