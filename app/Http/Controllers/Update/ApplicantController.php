<?php

namespace App\Http\Controllers\Update;

use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PelamarExport;
use App\Models\Agama;
use App\Models\HRD;
use App\Models\Karyawan;
use App\Models\Lowongan;
use App\Models\Pelamar;
use App\Models\Seleksi;
use App\Models\User;

class ApplicantController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            // Get applicants
            if(Auth::user()->role == role('admin')) {
                $hrd = HRD::find($request->query('hrd'));
                $applicants = $hrd ? Pelamar::join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('pelamar.id_hrd','=',$hrd->id_hrd)->orderBy('pelamar_at','desc')->get() : Pelamar::join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('pelamar_at','desc')->get();
            }
            elseif(Auth::user()->role == role('hrd')) {
                $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
                $applicants = Pelamar::join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('pelamar.id_hrd','=',$hrd->id_hrd)->orderBy('pelamar_at','desc')->get();
            }

            // Return
            return DataTables::of($applicants)
                ->addColumn('checkbox', '<input type="checkbox" class="form-check-input checkbox-one">')
                ->addColumn('name', '
                    <span class="d-none">{{ $nama_user }}</span>
                    <a href="{{ route(\'admin.applicant.detail\', [\'id\' => $id_pelamar]) }}">{{ ucwords($nama_user) }}</a>
                    <br>
                    <small class="text-muted"><i class="bi-envelope me-2"></i>{{ $email }}</small>
                    <br>
                    <small class="text-muted"><i class="bi-phone me-2"></i>{{ $nomor_hp }}</small>
                ')
                ->editColumn('posisi', '
                    {{ get_posisi_name($posisi) }}
                ')
                ->addColumn('datetime', '
                    <span class="d-none">{{ $pelamar_at != null ? $pelamar_at : "" }}</span>
                    {{ $pelamar_at != null ? date("d/m/Y", strtotime($pelamar_at)) : "-" }}
                    <br>
                    <small class="text-muted">{{ date("H:i", strtotime($pelamar_at))." WIB" }}</small>
                ')
                ->addColumn('company', '
                    {{ get_perusahaan_name($id_hrd) }}
                ')
                ->addColumn('options', '
                    <div class="btn-group">
                        <a href="{{ route(\'admin.applicant.detail\', [\'id\' => $id_pelamar]) }}" class="btn btn-sm btn-info" data-id="{{ $id_pelamar }}" data-bs-toggle="tooltip" title="Lihat Detail"><i class="bi-eye"></i></a>
                        <a href="{{ route(\'admin.applicant.edit\', [\'id\' => $id_pelamar]) }}" class="btn btn-sm btn-warning" data-id="{{ $id_pelamar }}" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $id_pelamar }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                    </div>
                ')
                ->removeColumn('password')
                ->rawColumns(['checkbox', 'name', 'username', 'posisi', 'datetime', 'company', 'options'])
                ->make(true);
        }

        // Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // View
        return view('admin/applicant/index', [
            'hrds' => $hrds
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // Get vacancies
        if(Auth::user()->role == role('admin')) {
            $vacancies = [];
        }
        elseif(Auth::user()->role == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $vacancies = Lowongan::where('id_hrd','=',$hrd->id_hrd)->where('status','=',1)->orderBy('judul_lowongan','asc')->get();
        }

        // View
        return view('admin/applicant/create', [
            'hrds' => $hrds,
            'vacancies' => $vacancies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'vacancy' => 'required',
            'name' => 'required|min:3|max:255',
            'birthdate' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'address' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Get the vacancy and HRD
            $vacancy = Lowongan::find($request->vacancy);
            $hrd = HRD::find($vacancy->id_hrd);
            
            // Generate username
            $data_user = User::where('has_access','=',0)->where('username','like', $hrd->kode.'%')->latest()->first();
            if(!$data_user)
                $username = generate_username(null, $hrd->kode);
            else
                $username = generate_username($data_user->username, $hrd->kode);

            // Save the user
            $user = new User;
            $user->nama_user = $request->name;
            $user->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $user->jenis_kelamin = $request->gender;
            $user->email = $request->email;
            $user->username = $username;
            $user->password = bcrypt($username);
            $user->password_str = $username;
            $user->foto = '';
            $user->role = role_pelamar();
            $user->has_access = 0;
            $user->status = 1;
            $user->last_visit = date("Y-m-d H:i:s");
            $user->created_at = date("Y-m-d H:i:s");
            $user->save();

            // Save the applicant
            $applicant = new Pelamar;
            $applicant->id_user = $user->id_user;
            $applicant->id_hrd = $hrd->id_hrd;
            $applicant->nama_lengkap = $request->name;
            $applicant->tempat_lahir = $request->birthplace != '' ? $request->birthplace : '';
            $applicant->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $applicant->jenis_kelamin = $request->gender;
            $applicant->agama = $request->religion;
            $applicant->email = $request->email;
            $applicant->nomor_hp = $request->phone_number;
            $applicant->nomor_telepon = '';
            $applicant->nomor_ktp = $request->identity_number != '' ? $request->identity_number : '';
            $applicant->status_hubungan = '';
            $applicant->alamat = $request->address;
            $applicant->pendidikan_terakhir = $request->latest_education != '' ? $request->latest_education : '';
            $applicant->riwayat_pekerjaan = $request->job_experience != '' ? $request->job_experience : '';
        	$applicant->akun_sosmed = '';
        	$applicant->data_darurat = '';
            $applicant->kode_pos = '';
            $applicant->pendidikan_formal = '';
            $applicant->pendidikan_non_formal = '';
            $applicant->riwayat_pekerjaan = '';
            $applicant->keahlian = '';
            $applicant->pertanyaan = '';
            $applicant->pas_foto = '';
            $applicant->foto_ijazah = '';
            $applicant->posisi = $vacancy->id_lowongan;
            $applicant->pelamar_at = date("Y-m-d H:i:s");
            $applicant->save();

            // Redirect
            return redirect()->route('admin.applicant.index')->with(['message' => 'Berhasil menambah data.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the applicant
        if(Auth::user()->role == role('admin'))
            $applicant = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->firstOrFail();
        else {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
    	    $applicant = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->where('id_hrd','=',$hrd->id_hrd)->firstOrFail();
        }

        if($applicant) {
            // Get the vacancy
            $applicant->posisi = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_lowongan','=',$applicant->posisi)->first();
			
			// Get the user
			$applicant->user = User::find($applicant->id_user);

            // Get the selection
            $selection = Seleksi::where('id_pelamar','=',$id)->where('id_lowongan','=',$applicant->posisi->id_lowongan)->first();

            // Set the applicant attribute
            $applicant->akun_sosmed = json_decode($applicant->akun_sosmed, true);
            $applicant->data_darurat = json_decode($applicant->data_darurat, true);
            $applicant->keahlian = json_decode($applicant->keahlian, true);
        }

        // View
        return view('admin/applicant/detail', [
            'applicant' => $applicant,
            'selection' => $selection
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Get the applicant
        if(Auth::user()->role == role('admin')) {
    	    $applicant = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->firstOrFail();
        }
        elseif(Auth::user()->role == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->firstOrFail();
    	    $applicant = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->where('id_hrd','=',$hrd->id_hrd)->firstOrFail();
        }

        // Set
        if($applicant) {
            $applicant->posisi = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_lowongan','=',$applicant->posisi)->first();
            $applicant->akun_sosmed = json_decode($applicant->akun_sosmed, true);
            $applicant->data_darurat = json_decode($applicant->data_darurat, true);
            $applicant->keahlian = json_decode($applicant->keahlian, true);
        }

        // View
        return view('admin/applicant/edit', [
            'applicant' => $applicant
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'birthplace' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'address' => 'required',
            'socmed' => 'required',
            'guardian_name' => 'required',
            'guardian_address' => 'required',
            'guardian_phone_number' => 'required|numeric',
            'guardian_occupation' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Set the applicant's socmed
            $socmed = [$request->platform => $request->socmed];

            // Set the applicant's guardian
        	$guardians = [];
        	$guardians['nama_orang_tua'] = $request->guardian_name;
        	$guardians['alamat_orang_tua'] = $request->guardian_address;
        	$guardians['nomor_hp_orang_tua'] = $request->guardian_phone_number;
        	$guardians['pekerjaan_orang_tua'] = $request->guardian_occupation;

            // Update the applicant
            $applicant = Pelamar::find($request->id);
            $applicant->nama_lengkap = $request->name;
            $applicant->tempat_lahir = $request->birthplace;
            $applicant->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $applicant->jenis_kelamin = $request->gender;
            $applicant->agama = $request->religion;
            $applicant->email = $request->email;
            $applicant->nomor_hp = $request->phone_number;
            $applicant->nomor_ktp = $request->identity_number != '' ? $request->identity_number : '';
            $applicant->alamat = $request->address;
            $applicant->pendidikan_terakhir = $request->latest_education != '' ? $request->latest_education : '';
            $applicant->riwayat_pekerjaan = $request->job_experience != '' ? $request->job_experience : '';
        	$applicant->akun_sosmed = json_encode($socmed);
        	$applicant->data_darurat = json_encode($guardians);
            $applicant->save();

            // Update the user
            $user = User::find($applicant->id_user);
            $user->nama_user = $request->name;
            $user->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $user->jenis_kelamin = $request->gender;
            $user->email = $request->email;
            $user->save();

            // Redirect
            return redirect()->route('admin.applicant.index')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the applicant
        $applicant = Pelamar::find($request->id);

        // Delete the applicant
        $applicant->delete();
        
        // Get the user
        $user = User::find($applicant->id_user);

        // Delete the user
        $user->delete();
        
        // Get the selection
        $selection = Seleksi::where('id_pelamar','=',$request->id)->first();

        // Delete the selection
        if($selection) $selection->delete();

        // Redirect
        return redirect()->route('admin.applicant.index')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Export to Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        ini_set("memory_limit", "-1");

        if(Auth::user()->role == role('admin')) {
            // Get the HRD
            $hrd = HRD::find($request->query('hrd'));

            // Get applicants
            $applicants = $hrd ? Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_hrd','=',$hrd->id_hrd)->get() : Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->get();

            // File name
            $filename = $hrd ? 'Data Pelamar '.$hrd->perusahaan.' ('.date('Y-m-d-H-i-s').')' : 'Data Semua Pelamar ('.date('d-m-Y-H-i-s').')';

            return Excel::download(new PelamarExport($applicants), $filename.'.xlsx');
        }
        elseif(Auth::user()->role == role('hrd')) {
            // Get the HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();

            // Get applicants
            $applicants = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_hrd','=',$hrd->id_hrd)->get();

            // File name
            $filename = 'Data Pelamar '.$hrd->perusahaan.' ('.date('Y-m-d-H-i-s').')';

            return Excel::download(new PelamarExport($applicants), $filename.'.xlsx');
        }
    }
}