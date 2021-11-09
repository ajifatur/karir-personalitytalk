<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\HRD;
use App\Models\Kantor;
use App\Models\Tes;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Menampilkan data admin
     * 
     * @return \Illuminate\Http\Response
     */
    public function admin()
    {
    	// Get data admin
    	$admin = User::where('role','=',role_admin())->get();

    	// View
    	return view('admin/index', [
    		'admin' => $admin,
    	]);
    }

    /**
     * Menampilkan data HRD
     * 
     * @return \Illuminate\Http\Response
     */
    public function hrd()
    {
        // Get data HRD
        //$hrd = User::where('role','=',2)->get();
        $hrd = HRD::join('users','hrd.id_user','=','users.id_user')->get();

        // View
        return view('hrd/admin/index', [
            'hrd' => $hrd,
        ]);
    }

    /**
     * Menampilkan data user umum
     * 
     * @return \Illuminate\Http\Response
     */
    public function general()
    {
		if(Auth::user()->username == 'ajifatur'){
			// Get data umum
			$user = User::where('role','=',5)->get();

			// View
			if(Auth::user()->role == role_admin()){
				return view('umum/index', [
					'user' => $user,
				]);
			}
		}
		else{
			abort(404);
		}
    }

    /**
     * Menampilkan form input admin
     *
     * @return \Illuminate\Http\Response
     */
    public function createAdmin()
    {
        // View
        return view('admin/create');
    }

    /**
     * Menampilkan form input HRD
     *
     * @return \Illuminate\Http\Response
     */
    public function createHRD()
    {
    	// Get data tes
    	$tes = Tes::all();
    	
        // View
        if(Auth::user()->role == role_admin()){
            return view('hrd/admin/create', ['tes' => $tes]);
        }
        elseif(Auth::user()->role == role_hrd()){
            return view('hrd/hrd/create', ['tes' => $tes]);
        }
    }

    /**
     * Menyimpan data admin...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdmin(Request $request)
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
            
            // Menambah data
            $admin = new User;
            $admin->nama_user = $request->nama;
            $admin->tanggal_lahir = $request->tanggal_lahir;
            $admin->jenis_kelamin = $request->jenis_kelamin;
            $admin->email = $request->email;
            $admin->username = $request->username;
            $admin->password = bcrypt($request->password);
            $admin->foto = $file_name != '' ? $file_name : $request->foto;
            $admin->role = 1;
            $admin->has_access = 1;
            $admin->created_at = date("Y-m-d H:i:s");
            $admin->save();
        }

        // Redirect
        return redirect('admin/list')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Menyimpan data HRD...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeHRD(Request $request)
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
            $user->tanggal_lahir = $request->tanggal_lahir;
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->foto = $file_name != '' ? $file_name : $request->foto;
            $user->role = 2;
            $user->has_access = 1;
            $user->created_at = date("Y-m-d H:i:s");
            $user->save();
            
            // Mengambil data user
            $data_user = User::where('username','=',$request->username)->first();
            
            // Menambah data HRD
            $hrd = new HRD;
            $hrd->id_user = $data_user->id_user;
            $hrd->nama_lengkap = $request->nama;
            $hrd->tanggal_lahir = $request->tanggal_lahir;
            $hrd->jenis_kelamin = $request->jenis_kelamin;
            $hrd->email = $request->email;
            $hrd->kode = $request->kode;
            $hrd->perusahaan = $request->perusahaan != '' ? $request->perusahaan : '';
            $hrd->alamat_perusahaan = $request->alamat_perusahaan != '' ? $request->alamat_perusahaan : '';
            $hrd->telepon_perusahaan = $request->telepon_perusahaan != '' ? $request->telepon_perusahaan : '';
            $hrd->akses_tes = !empty($request->get('tes')) ? implode(',', array_filter($request->get('tes'))) : '';
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
        if(Auth::user()->role == role_admin()){
            return redirect('admin/hrd')->with(['message' => 'Berhasil menambah data.']);
        }
        elseif(Auth::user()->role == role_hrd()){
            return redirect('hrd/list')->with(['message' => 'Berhasil menambah data.']);
        }
    }

    /**
     * Menampilkan form edit admin
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function editAdmin($id)
    {
    	// Get data admin
    	$admin = User::find($id);

    	// Jika tidak ada data
    	if(!$admin){
    		abort(404);
    	}

        // View
        return view('admin/edit', ['admin' => $admin]);
    }

    /**
     * Menampilkan form edit HRD
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function editHRD($id)
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
            return view('hrd/admin/edit', [
                'hrd' => $hrd,
                'tes' => $tes,
            ]);
        }
        elseif(Auth::user()->role == role_hrd()){
            return view('hrd/hrd/edit', [
                'hrd' => $hrd,
                'tes' => $tes,
            ]);
        }
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
        return view('admin/edit-profil', [
            'user' => $user,
        ]);
    }

    /**
     * Mengupdate data admin...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAdmin(Request $request)
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
            'password' => $request->password != '' ? 'required|min:4' : '',
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
            $admin = User::find($request->id);
            $admin->nama_user = $request->nama;
            $admin->tanggal_lahir = $request->tanggal_lahir;
            $admin->jenis_kelamin = $request->jenis_kelamin;
            $admin->email = $request->email;
            $admin->username = $request->username;
            $admin->password = $request->password != '' ? bcrypt($request->password) : $admin->password;
            $admin->foto = $file_name != '' ? $file_name : $request->foto;
            $admin->save();
        }

        // Redirect
        return redirect('admin/list')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Mengupdate data HRD...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateHRD(Request $request)
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
            'password' => $request->password != '' ? 'required|min:4' : '',
            // 'file' => $request->foto == '' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '',
            'kode' => 'required|alpha|min:3|max:4',
            'perusahaan' => 'required|string',
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
            $user->tanggal_lahir = $request->tanggal_lahir;
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = $request->password != '' ? bcrypt($request->password) : $user->password;
            $user->foto = $file_name != '' ? $file_name : $request->foto;
            $user->save();
            
            // Mengupdate data HRD
            $hrd = HRD::where('id_user','=',$request->id)->first();
            $hrd->nama_lengkap = $request->nama;
            $hrd->tanggal_lahir = $request->tanggal_lahir;
            $hrd->jenis_kelamin = $request->jenis_kelamin;
            $hrd->email = $request->email;
            $hrd->kode = $request->kode;
            $hrd->perusahaan = $request->perusahaan != '' ? $request->perusahaan : '';
            $hrd->alamat_perusahaan = $request->alamat_perusahaan != '' ? $request->alamat_perusahaan : '';
            $hrd->telepon_perusahaan = $request->telepon_perusahaan != '' ? $request->telepon_perusahaan : '';
            $hrd->akses_tes = !empty($request->get('tes')) ? implode(',', array_filter($request->get('tes'))) : '';
            $hrd->save();
            
            // Mengupdate data kantor (Head Office)
            $kantor = Kantor::where('id_hrd','=',$hrd->id_hrd)->where('nama_kantor','=','Head Office')->first();
			$kantor->alamat_kantor = $request->alamat_perusahaan != '' ? $request->alamat_perusahaan : '';
			$kantor->telepon_kantor = $request->telepon_perusahaan != '' ? $request->telepon_perusahaan : '';
			$kantor->save();
        }

        // Redirect
        if(Auth::user()->role == role_admin()){
            return redirect('admin/hrd')->with(['message' => 'Berhasil mengupdate data.']);
        }
        elseif(Auth::user()->role == role_hrd()){
            return redirect('hrd/list')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Mengupdate profil...
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
            'password' => $request->password != '' ? 'required|min:4' : '',
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
            $user->tanggal_lahir = $request->tanggal_lahir;
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = $request->password != '' ? bcrypt($request->password) : $user->password;
            $user->foto = $file_name != '' ? $file_name : $request->foto;
            $user->save();
            
			// Mengupdate data HRD
			if($request->role == role_hrd()){
				$hrd = HRD::where('id_user','=',$request->id)->first();
				$hrd->nama_lengkap = $request->nama;
				$hrd->tanggal_lahir = $request->tanggal_lahir;
				$hrd->jenis_kelamin = $request->jenis_kelamin;
				$hrd->email = $request->email;
				$hrd->save();
			}
        }

        // Redirect
        if(Auth::user()->role == role_admin()){
            return redirect('admin/edit-profil')->with(['message' => 'Berhasil mengupdate data.']);
        }
        elseif(Auth::user()->role == role_hrd()){
            return redirect('hrd/edit-profil')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Menghapus user...
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
        
        if($user->delete()){
            echo "Berhasil menghapus data!";
        }
    }
}
