<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\HRD;
use App\Kantor;
use App\Tes;
use App\User;

class HRDController extends Controller
{
    /**
     * Menampilkan data HRD
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get data HRD
        //$hrd = User::where('role','=',2)->get();
        $hrd = HRD::join('users','hrd.id_user','=','users.id_user')->get();

        // View
        if(Auth::user()->role == role_admin()){
            return view('hrd/index', [
                'hrd' => $hrd,
            ]);
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Menampilkan form input HRD
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	// Get data tes
    	$tes = Tes::all();
    	
        // View
        if(Auth::user()->role == role_admin()){
            return view('hrd/create', ['tes' => $tes]);
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Menyimpan data HRD
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3|max:255',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|min:4|unique:users',
            'password' => 'required|min:4',
            // 'file' => $request->foto == '' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '',
            'kode' => 'required|alpha|min:3|max:4',
            'perusahaan' => 'required|string',
            'stifin' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Upload foto
            $file = $request->file('file');
            $file_name = '';
            if(!empty($file)){
                $destination_dir = 'assets/images/foto-user/';
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move($destination_dir, $file_name);
            }
            
            // Menambah data user
            $user = new User;
            $user->nama_user = $request->nama;
            $user->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->foto = $file_name != '' ? $file_name : $request->foto;
            $user->role = role_hrd();
            $user->has_access = 1;
            $user->status = 1;
            $user->last_visit = date("Y-m-d H:i:s");
            $user->created_at = date("Y-m-d H:i:s");
            $user->save();
            
            // Mengambil data user
            $data_user = User::where('username','=',$request->username)->first();
            
            // Menambah data HRD
            $hrd = new HRD;
            $hrd->id_user = $data_user->id_user;
            $hrd->nama_lengkap = $request->nama;
            $hrd->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $hrd->jenis_kelamin = $request->jenis_kelamin;
            $hrd->email = $request->email;
            $hrd->kode = $request->kode;
            $hrd->perusahaan = $request->perusahaan != '' ? $request->perusahaan : '';
            $hrd->alamat_perusahaan = $request->alamat_perusahaan != '' ? $request->alamat_perusahaan : '';
            $hrd->telepon_perusahaan = $request->telepon_perusahaan != '' ? $request->telepon_perusahaan : '';
            $hrd->akses_tes = !empty($request->get('tes')) ? implode(',', array_filter($request->get('tes'))) : '';
            $hrd->akses_stifin = $request->stifin;
            $hrd->save();
            
            // Mengambil data HRD
            $data_hrd = HRD::where('id_user','=',$data_user->id_user)->first();
            
            // Menambah data kantor
            $kantor = new Kantor;
			$kantor->id_hrd = $data_hrd->id_hrd;
			$kantor->nama_kantor = 'Head Office';
			$kantor->alamat_kantor = $request->alamat_perusahaan != '' ? $request->alamat_perusahaan : '';
			$kantor->telepon_kantor = $request->telepon_perusahaan != '' ? $request->telepon_perusahaan : '';
			$kantor->save();
        }

        // Redirect
        return redirect('admin/hrd')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Menampilkan form edit HRD
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get data HRD
        $hrd = HRD::join('users','hrd.id_user','=','users.id_user')->where('users.id_user','=',$id)->first();

    	// Get data tes
    	$tes = Tes::all();

        // Jika tidak ada data HRD
        if(!$hrd){
            abort(404);
        }
        $hrd->akses_tes = $hrd->akses_tes != '' ? explode(',', $hrd->akses_tes) : array();

        // View
        if(Auth::user()->role == role_admin()){
            return view('hrd/edit', [
                'hrd' => $hrd,
                'tes' => $tes,
            ]);
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Mengupdate data HRD
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Data HRD
        $hrd = HRD::where('id_user','=',$request->id)->first();

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3|max:255',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($request->id, 'id_user'),
            ],
            'username' => [
                'required',
                'string',
                'min:4',
                Rule::unique('users')->ignore($request->id, 'id_user'),
            ],
            'password' => $request->password != '' ? 'required|min:4' : '',
            // 'file' => $request->foto == '' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '',
            'kode' => [
                'required',
                'alpha',
                'min:3',
                'max:4',
                Rule::unique('hrd')->ignore($hrd->id_hrd, 'id_hrd'),
            ],
            'perusahaan' => 'required|string',
            'stifin' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Upload foto
            $file = $request->file('file');
            $file_name = '';
            if(!empty($file)){
                $destination_dir = 'assets/images/foto-user/';
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move($destination_dir, $file_name);
            }
            
            // Mengupdate data user
            $user = User::find($request->id);
            $user->nama_user = $request->nama;
            $user->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = $request->password != '' ? bcrypt($request->password) : $user->password;
            $user->foto = $file_name != '' ? $file_name : $request->foto;
            $user->save();
            
            // Mengupdate data HRD
            $hrd = HRD::where('id_user','=',$request->id)->first();
            $hrd->nama_lengkap = $request->nama;
            $hrd->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $hrd->jenis_kelamin = $request->jenis_kelamin;
            $hrd->email = $request->email;
            $hrd->kode = $request->kode;
            $hrd->perusahaan = $request->perusahaan != '' ? $request->perusahaan : '';
            $hrd->alamat_perusahaan = $request->alamat_perusahaan != '' ? $request->alamat_perusahaan : '';
            $hrd->telepon_perusahaan = $request->telepon_perusahaan != '' ? $request->telepon_perusahaan : '';
            $hrd->akses_tes = !empty($request->get('tes')) ? implode(',', array_filter($request->get('tes'))) : '';
            $hrd->akses_stifin = $request->stifin;
            $hrd->save();
            
            // Mengupdate data kantor (Head Office)
            $kantor = Kantor::where('id_hrd','=',$hrd->id_hrd)->where('nama_kantor','=','Head Office')->first();
			$kantor->alamat_kantor = $request->alamat_perusahaan != '' ? $request->alamat_perusahaan : '';
			$kantor->telepon_kantor = $request->telepon_perusahaan != '' ? $request->telepon_perusahaan : '';
			$kantor->save();
        }

        // Redirect
        return redirect('admin/hrd')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Menghapus user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $user = User::find($request->id);
        
        if($user->role == role_hrd()){
            $hrd = HRD::where('id_user','=',$request->id)->first();
            $hrd->delete();
        }
        $user->delete();
        
        // Redirect
        return redirect('admin/hrd')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Menampilkan halaman profil
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        // Get data profil
        $user = User::find(Auth::user()->id_user);

        if($user->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }

        // View
        return view('hrd/profile', [
            'user' => $user,
            'hrd' => Auth::user()->role == role_hrd() ? $hrd : null,
        ]);
    }

    /**
     * Menampilkan form edit profil
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfil()
    {
        // Get data profil
        $user = User::find(Auth::user()->id_user);

        // View
        return view('hrd/edit-profil', [
            'user' => $user,
        ]);
    }

    /**
     * Mengupdate profil
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfil(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3|max:255',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($request->id, 'id_user'),
            ],
            'username' => [
                'required',
                'string',
                'min:4',
                Rule::unique('users')->ignore($request->id, 'id_user'),
            ],
            // 'file' => $request->foto == '' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Upload foto
            $file = $request->file('file');
            $file_name = '';
            if(!empty($file)){
                $destination_dir = 'assets/images/foto-user/';
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move($destination_dir, $file_name);
            }
            
            // Mengupdate data user
            $user = User::find($request->id);
            $user->nama_user = $request->nama;
            $user->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->foto = $file_name != '' ? $file_name : $user->foto;
            $user->save();
            
            // Mengupdate data HRD
            if($request->role == role_hrd()){
                $hrd = HRD::where('id_user','=',$request->id)->first();
                $hrd->nama_lengkap = $request->nama;
                $hrd->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
                $hrd->jenis_kelamin = $request->jenis_kelamin;
                $hrd->email = $request->email;
                $hrd->save();
            }
        }

        // Redirect
        return redirect('admin/profil/edit')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Menampilkan form edit password
     *
     * @return \Illuminate\Http\Response
     */
    public function editPassword()
    {
        // Get data profil
        $user = User::find(Auth::user()->id_user);

        // View
        return view('hrd/edit-password', [
            'user' => $user,
        ]);
    }

    /**
     * Mengupdate password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:4',
            'new_password' => 'required|min:4',
            'confirm_password' => 'required|min:4|same:new_password',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Data user
            $user = User::find(Auth::user()->id_user);

            // Hash password
            $old_password = $user->password;
            $new_password = bcrypt($request->new_password);

            // Cek password lama, untuk alasan keamanan
            $check = Hash::check($request->old_password, $user->password);

            // Jika password lama yang diinputkan cocok dengan password saat ini
            if($check){
                // Mengupdate password baru
                $user->password = $new_password;
                $user->save();

                // Redirect
                return redirect('/admin/profil/edit-password')->with(['message' => 'Berhasil mengupdate password.', 'status' => 1]);
            }
            // Jika password lama yang diinputkan TIDAK cocok dengan password saat ini
            else{
                // Redirect
                return redirect('/admin/profil/edit-password')->with(['message' => 'Password lama yang diinput tidak cocok dengan password yang dimiliki saat ini.', 'status' => 0]);
            }
        }
    }
}
