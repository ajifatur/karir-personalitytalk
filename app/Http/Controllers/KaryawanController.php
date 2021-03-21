<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KaryawanExport;
use App\Imports\KaryawanImport;
use App\HRD;
use App\Karyawan;
use App\Kantor;
use App\Pelamar;
use App\Posisi;
use App\User;

class KaryawanController extends Controller
{
    /**
     * Menampilkan JSON data karyawan
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function json(Request $request)
    {
    	// Get data karyawan
        if(Auth::user()->role == role_admin()){
			if($request->query('hrd') != null){
            	$hrd = HRD::find($request->query('hrd'));
    	    	$karyawan = $hrd ? Karyawan::join('users','karyawan.id_user','=','users.id_user')->where('id_hrd','=',$request->query('hrd'))->get() : Karyawan::join('users','karyawan.id_user','=','users.id_user')->get();
			}
			else{
    	    	$karyawan = Karyawan::join('users','karyawan.id_user','=','users.id_user')->get();
			}
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $karyawan = Karyawan::join('users','karyawan.id_user','=','users.id_user')->where('id_hrd','=',$hrd->id_hrd)->get();
			$kantor = Kantor::where('id_hrd','=',$hrd->id_hrd)->get();
        }
        
        // Return
        return DataTables::of($karyawan)
        ->addColumn('checkbox', '<input type="checkbox">')
        ->addColumn('name', '
            <span class="d-none">{{ $nama_user }}</span>
            <a href="/admin/karyawan/detail/{{ $id_karyawan }}">{{ ucwords($nama_user) }}</a>
            <br>
            <small class="text-muted"><i class="fa fa-envelope mr-2"></i>{{ $email }}</small>
            <br>
            <small class="text-muted"><i class="fa fa-phone mr-2"></i>{{ $nomor_hp }}</small>
        ')
        ->editColumn('posisi', '
            {{ get_posisi_name($posisi) }}
        ')
        ->editColumn('status', '
            <span class="badge {{ $status == 1 ? "badge-success" : "badge-danger" }}">{{ $status == 1 ? "Aktif" : "Tidak Aktif" }}</span>
        ')
        ->addColumn('company', '
            {{ get_perusahaan_name($id_hrd) }}
            <br>
            <small class="text-muted">{{ get_hrd_name($id_hrd) }}</small>
        ')
        ->addColumn('options', '
            <div class="btn-group">
                <a href="/admin/karyawan/detail/{{ $id_karyawan }}" class="btn btn-sm btn-info" data-id="{{ $id_karyawan }}" data-toggle="tooltip" title="Lihat Detail"><i class="fa fa-eye"></i></a>
                <a href="/admin/karyawan/edit/{{ $id_karyawan }}" class="btn btn-sm btn-warning" data-id="{{ $id_karyawan }}" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $id_karyawan }}" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>
            </div>
        ')
        ->removeColumn('password')
        ->rawColumns(['checkbox', 'name', 'username', 'posisi', 'status', 'company', 'options'])
        ->make(true);
    }
    /**
     * Menampilkan data karyawan
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	// View
        return view('karyawan/index');
    }

    /**
     * Menampilkan form input karyawan
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	// Get data HRD
    	$hrd = HRD::all();
    	
    	// Get data jabatan dan kantor
        if(Auth::user()->role == role_admin()){
            $posisi = Posisi::orderBy('nama_posisi','asc')->get();
            $kantor = null;
        }
        elseif(Auth::user()->role == role_hrd()){
            $user_hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $posisi = Posisi::where('id_hrd','=',$user_hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
            $kantor = Kantor::where('id_hrd','=',$user_hrd->id_hrd)->get();
        }
        
        // View
        return view('karyawan/create', [
            'hrd' => $hrd,
            'posisi' => $posisi,
            'kantor' => $kantor,
        ]);
    }

    /**
     * Menyimpan karyawan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	// Get data HRD
    	if(Auth::user()->role == role_admin()){
            $hrd = HRD::find($request->hrd);
        }
    	if(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'email' => 'required|email',
            'nomor_hp' => 'required|numeric',
            'jabatan' => Auth::user()->role == role_hrd() ? 'required' : '',
            'kantor' => Auth::user()->role == role_hrd() ? 'required' : '',
            'hrd' => Auth::user()->role == role_admin() ? 'required' : '',
            // 'file' => Auth::user()->role == role_hrd() ? $request->foto == '' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '' : '',
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
                $destination_dir = 'assets/images/foto-karyawan/';
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move($destination_dir, $file_name);
            }
            
            // Generate username
            $data_user = User::where('has_access','=',0)->where('username','like', $hrd->kode.'%')->latest()->first();
            if(!$data_user){
                $username = generate_username(null, $hrd->kode);
            }
            else{
                $username = generate_username($data_user->username, $hrd->kode);
            }
            
            // Menambah data user
            $user = new User;
            $user->nama_user = $request->nama_lengkap;
            $user->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->email = $request->email;
            $user->username = $username;
            $user->password_str = $username;
            $user->password = bcrypt($username);
            $user->foto = $file_name != '' ? $file_name : $request->foto;
            $user->role = role_karyawan();
            $user->has_access = 0;
            $user->status = $request->status;
            $user->last_visit = date("Y-m-d H:i:s");
            $user->created_at = date("Y-m-d H:i:s");
            $user->save();
            
            // Ambil data akun karyawan
            $akun = User::where('username','=',$user->username)->first();
            
            // Menambah data karyawan
            $karyawan = new Karyawan;
            $karyawan->id_user = $akun->id_user;
            $karyawan->id_hrd = isset($hrd) ? $hrd->id_hrd : $request->hrd;
            $karyawan->nama_lengkap = $request->nama_lengkap;
            $karyawan->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $karyawan->jenis_kelamin = $request->jenis_kelamin;
            $karyawan->email = $request->email;
            $karyawan->nomor_hp = $request->nomor_hp;
            $karyawan->posisi = Auth::user()->role == role_hrd() ? $request->jabatan : 0;
            $karyawan->kantor = Auth::user()->role == role_hrd() ? $request->kantor : 0;
            $karyawan->nik_cis = $request->nik_cis != '' ? $request->nik_cis : '';
            $karyawan->nik = $request->nik != '' ? $request->nik : '';
            $karyawan->alamat = $request->alamat != '' ? $request->alamat : '';
            $karyawan->pendidikan_terakhir = $request->pendidikan_terakhir != '' ? $request->pendidikan_terakhir : '';
            $karyawan->awal_bekerja = $request->awal_bekerja != '' ? generate_date_format($request->awal_bekerja, 'y-m-d') : null;
            $karyawan->save();
        }

        // Redirect
        return redirect('admin/karyawan')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Menampilkan detail karyawan
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // Get data karyawan
        if(Auth::user()->role == role_admin()){
            $karyawan = Karyawan::find($id);
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $karyawan = Karyawan::where('id_karyawan','=',$id)->where('id_hrd','=',$hrd->id_hrd)->first();
        }

        // Jika tidak ada data
        if(!$karyawan){
            abort(404);
        }
        // Jika ada data
        else{
            $karyawan->user = User::find($karyawan->id_user);
            $karyawan->kantor = Kantor::find($karyawan->kantor);
            $karyawan->posisi = Posisi::find($karyawan->posisi);
            
            // View
            return view('karyawan/detail', [
                'karyawan' => $karyawan,
            ]);
        }
    }

    /**
     * Menampilkan form edit karyawan
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	// Get data HRD dan karyawan
    	if(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $karyawan = Karyawan::join('users','karyawan.id_user','=','users.id_user')->where('id_karyawan','=',$id)->where('id_hrd','=',$hrd->id_hrd)->first();
        }
        else{
            $karyawan = Karyawan::join('users','karyawan.id_user','=','users.id_user')->find($id);
        }
        
    	// Get data jabatan
        if(Auth::user()->role == role_admin()){
            $hrd = HRD::find($karyawan->id_hrd);
            $posisi = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
            $kantor = Kantor::where('id_hrd','=',$hrd->id_hrd)->get();
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $posisi = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
            $kantor = Kantor::where('id_hrd','=',$hrd->id_hrd)->get();
        }

        // Jika tidak ada data
        if(!$karyawan){
            abort(404);
        }
        else{
            $user = User::find($karyawan->id_user);
            $karyawan->foto = $user->foto;
        }

        // View
        return view('karyawan/edit', [
            'karyawan' => $karyawan,
            'posisi' => $posisi,
            'kantor' => $kantor,
        ]);
    }

    /**
     * Mengupdate karyawan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'email' => 'required|email',
            'nomor_hp' => 'required|numeric',
            'status' => 'required',
            'jabatan' => 'required',
            'kantor' => 'required',
            // 'file' => Auth::user()->role == role_hrd() ? $request->foto == '' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '' : '',
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
                $destination_dir = 'assets/images/foto-karyawan/';
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move($destination_dir, $file_name);
            }
            
            // Mengupdate data karyawan
            $karyawan = Karyawan::find($request->id);
            $karyawan->nama_lengkap = $request->nama_lengkap;
            $karyawan->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $karyawan->jenis_kelamin = $request->jenis_kelamin;
            $karyawan->email = $request->email;
            $karyawan->nomor_hp = $request->nomor_hp;
            $karyawan->nik_cis = $request->nik_cis != '' ? $request->nik_cis : '';
            $karyawan->nik = $request->nik != '' ? $request->nik : '';
            $karyawan->alamat = $request->alamat != '' ? $request->alamat : '';
            $karyawan->pendidikan_terakhir = $request->pendidikan_terakhir != '' ? $request->pendidikan_terakhir : '';
            $karyawan->awal_bekerja = $request->awal_bekerja != '' ? generate_date_format($request->awal_bekerja, 'y-m-d') : null;
            $karyawan->posisi = $request->jabatan;
            $karyawan->kantor = $request->kantor;
            $karyawan->save();
            
            // Mengupdate data user
            $user = User::find($karyawan->id_user);
            $user->nama_user = $request->nama_lengkap;
            $user->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->email = $request->email;
            $user->foto = $file_name != '' ? $file_name : $user->foto;
            $user->status = $request->status;
            $user->save();
        }

        // Redirect
        return redirect('admin/karyawan')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Menghapus data karyawan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $karyawan = Karyawan::find($request->id);
        $karyawan->delete();
        $user = User::find($karyawan->id_user);
        $user->delete();
        $pelamar = Pelamar::where('id_user','=',$karyawan->id_user)->first();
        if($pelamar) $pelamar->delete();

        // Redirect
        return redirect('admin/karyawan')->with(['message' => 'Berhasil menghapus data.']);
    }
    
    /**
     * Export ke Excel
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        ini_set("memory_limit", "-1");

        if(Auth::user()->role == role_admin()){
            // Cek HRD
            $hrd = HRD::find($request->query('hrd'));

            // Get data karyawan
            if($hrd)
                $karyawan = Karyawan::where('id_hrd','=',$hrd->id_hrd)->get();
            else
                $karyawan = Karyawan::get();

            // Nama file
            $nama_file = $hrd ? 'Data Karyawan '.$hrd->perusahaan.' ('.date('Y-m-d-H-i-s').')' : 'Data Semua Karyawan ('.date('d-m-Y-H-i-s').')';

            return Excel::download(new KaryawanExport($karyawan), $nama_file.'.xlsx');
        }
        elseif(Auth::user()->role == role_hrd()){
            // Cek HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();

            // Data karyawan
            $karyawan = Karyawan::where('id_hrd','=',$hrd->id_hrd)->get();

            // Nama file
            $nama_file = 'Data Karyawan '.$hrd->perusahaan.' ('.date('Y-m-d-H-i-s').')';

            return Excel::download(new KaryawanExport($karyawan), $nama_file.'.xlsx');
        }
    }
 
    /**
     * Import dari Excel
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request) 
    {        
        ini_set('max_execution_time', 600);

        // Data HRD
        $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();

        // Mengkonversi data di Excel ke bentuk array
        $array = Excel::toArray(new KaryawanImport, $request->file('file'));

        if(count($array)>0){
            foreach($array[0] as $data){
                // Mengecek data user berdasarkan id
                $user = User::where('role','=',role_karyawan())->find($data[0]);

                // Jika data user tidak ditemukan
                if(!$user){

                    // Konversi format tanggal lahir
                    if($data[3] != ''){
                        $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[3]);
                        $tanggal_lahir = (array)$tanggal_lahir;
                    }

                    // Konversi format awal bekerja
                    if($data[9] != ''){
                        $awal_bekerja = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[9]);
                        $awal_bekerja = (array)$awal_bekerja;
                    }

                    // Generate username
                    $data_user = User::where('has_access','=',0)->where('username','like', $hrd->kode.'%')->latest()->first();
                    if(!$data_user){
                        $username = generate_username(null, $hrd->kode);
                    }
                    else{
                        $username = generate_username($data_user->username, $hrd->kode);
                    }
        
                    // Menambah data user
                    $user = new User;
                    $user->nama_user = $data[2];
                    $user->tanggal_lahir = $data[3] != '' ? date('Y-m-d', strtotime($tanggal_lahir['date'])) : null;
                    $user->jenis_kelamin = $data[4];
                    $user->email = $data[5];
                    $user->username = $username;
                    $user->password_str = $username;
                    $user->password = bcrypt($username);
                    $user->foto = '';
                    $user->role = role_karyawan();
                    $user->has_access = 0;
                    $user->created_at = date("Y-m-d H:i:s");
                    $user->save();
        
                    // Ambil data akun karyawan
                    $akun = User::where('username','=',$user->username)->first();
        
                    // Menambah data karyawan
                    $karyawan = new Karyawan;
                    $karyawan->id_user = $akun->id_user;
                    $karyawan->id_hrd = $hrd->id_hrd;
                    $karyawan->nama_lengkap = $akun->nama_user;
                    $karyawan->tanggal_lahir = $akun->tanggal_lahir;
                    $karyawan->jenis_kelamin = $akun->jenis_kelamin;
                    $karyawan->email = $akun->email;
                    $karyawan->nomor_hp = $data[6];
                    $karyawan->nik_cis = '';
                    $karyawan->nik = '';
                    $karyawan->alamat = $data[7] != '' ? $data[7] : '';
                    $karyawan->pendidikan_terakhir = $data[8] != '' ? $data[8] : '';
                    $karyawan->awal_bekerja = $data[9] != '' ? date('Y-m-d', strtotime($awal_bekerja['date'])) : null;
                    $karyawan->posisi = get_posisi_id($hrd->id_hrd, $data[10]);
                    $karyawan->kantor = get_kantor_id($hrd->id_hrd, $data[11]);
                    $karyawan->save();
                }
                // Jika data user ditemukan
                else{
                    // Mengupdate data user
                    $user = User::find($data[0]);
                    $user->nama_user = $data[2];
                    $user->tanggal_lahir = $data[3] != '' ? generate_date_format($data[3], 'y-m-d') : '';
                    $user->jenis_kelamin = $data[4];
                    $user->email = $data[5];
                    $user->save();

                    // Mengupdate data karyawan
                    $karyawan = Karyawan::where('id_user','=',$user->id_user)->first();
                    $karyawan->nama_lengkap = $user->nama_user;
                    $karyawan->tanggal_lahir = $user->tanggal_lahir;
                    $karyawan->jenis_kelamin = $user->jenis_kelamin;
                    $karyawan->email = $user->email;
                    $karyawan->nomor_hp = $data[6];
                    $karyawan->alamat = $data[7] != '' ? $data[7] : '';
                    $karyawan->pendidikan_terakhir = $data[8] != '' ? $data[8] : '';
                    $karyawan->awal_bekerja = $data[9] != '' ? generate_date_format($data[9], 'y-m-d') : null;
                    $karyawan->posisi = get_posisi_id($hrd->id_hrd, $data[10]);
                    $karyawan->kantor = get_kantor_id($hrd->id_hrd, $data[11]);
                    $karyawan->save();
                }
            }

            // Redirect
            return redirect('/admin/karyawan')->with(['message' => 'Berhasil mengimpor data.']);
        }
        else{
            // Redirect
            return redirect('/admin/karyawan')->with(['message' => 'Impor data gagal! Data di Excel kosong.']);
        }
    }
}
