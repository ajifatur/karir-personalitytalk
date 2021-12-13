<?php

namespace App\Http\Controllers\Update;

use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KaryawanExport;
use App\Imports\KaryawanImport;
use App\Models\Karyawan;
use App\Models\HRD;
use App\Models\Kantor;
use App\Models\Pelamar;
use App\Models\Posisi;
use App\Models\User;

class EmployeeController extends \App\Http\Controllers\Controller
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
            // Get employees
            if(Auth::user()->role == role('admin')) {
                $hrd = HRD::find($request->query('hrd'));
                $employees = $hrd ? Karyawan::join('users','karyawan.id_user','=','users.id_user')->where('id_hrd','=',$hrd->id_hrd)->get() : Karyawan::join('users','karyawan.id_user','=','users.id_user')->get();
            }
            elseif(Auth::user()->role == role('hrd')) {
                $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
                $employees = Karyawan::join('users','karyawan.id_user','=','users.id_user')->where('id_hrd','=',$hrd->id_hrd)->get();
            }

            // Return
            return DataTables::of($employees)
                ->addColumn('checkbox', '<input type="checkbox" class="form-check-input checkbox-one">')
                ->addColumn('name', '
                    <span class="d-none">{{ $nama_user }}</span>
                    <a href="{{ route(\'admin.employee.detail\', [\'id\' => $id_karyawan]) }}">{{ ucwords($nama_user) }}</a>
                    <br>
                    <small class="text-muted"><i class="bi-envelope me-2"></i>{{ $email }}</small>
                    <br>
                    <small class="text-muted"><i class="bi-phone me-2"></i>{{ $nomor_hp }}</small>
                ')
                ->editColumn('posisi', '
                    {{ get_posisi_name($posisi) }}
                ')
                ->editColumn('status', '
                    <span class="badge {{ $status == 1 ? "bg-success" : "bg-danger" }}">{{ $status == 1 ? "Aktif" : "Tidak Aktif" }}</span>
                ')
                ->addColumn('company', '
                    {{ get_perusahaan_name($id_hrd) }}
                ')
                ->addColumn('options', '
                    <div class="btn-group">
                        <a href="{{ route(\'admin.employee.detail\', [\'id\' => $id_karyawan]) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Lihat Detail"><i class="bi-eye"></i></a>
                        <a href="{{ route(\'admin.employee.edit\', [\'id\' => $id_karyawan]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $id_karyawan }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                    </div>
                ')
                ->removeColumn('password')
                ->rawColumns(['checkbox', 'name', 'username', 'posisi', 'status', 'company', 'options'])
                ->make(true);
        }

        // Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // View
        return view('admin/employee/index', [
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

        // Get HRD
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // Get tests
        if(Auth::user()->role == role('admin')) {
            $positions = Posisi::orderBy('nama_posisi','asc')->get();
            $offices = null;
        }
        elseif(Auth::user()->role == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $positions = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
            $offices = Kantor::where('id_hrd','=',$hrd->id_hrd)->get();
        }

        // View
        return view('admin/employee/create', [
            'hrds' => $hrds,
            'positions' => $positions,
            'offices' => $offices,
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
    	// Get the HRD
    	if(Auth::user()->role == role('admin')) {
            $hrd = HRD::find($request->hrd);
        }
    	elseif(Auth::user()->role == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'status' => 'required',
            'hrd' => Auth::user()->role == role('admin') ? 'required' : '',
            'office' => Auth::user()->role == role('hrd') ? 'required' : '',
            'position' => Auth::user()->role == role('hrd') ? 'required' : '',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Generate username
            $userdata = User::where('has_access','=',0)->where('username','like', $hrd->kode.'%')->latest()->first();
            if(!$userdata){
                $username = generate_username(null, $hrd->kode);
            }
            else{
                $username = generate_username($userdata->username, $hrd->kode);
            }

            // Save the user
            $user = new User;
            $user->nama_user = $request->name;
            $user->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $user->jenis_kelamin = $request->gender;
            $user->email = $request->email;
            $user->username = $username;
            $user->password_str = $username;
            $user->password = bcrypt($username);
            $user->foto = '';
            $user->role = role('employee');
            $user->has_access = 0;
            $user->status = $request->status;
            $user->last_visit = date("Y-m-d H:i:s");
            $user->created_at = date("Y-m-d H:i:s");
            $user->save();

            // Save the employee
            $employee = new Karyawan;
            $employee->id_user = $user->id_user;
            $employee->id_hrd = isset($hrd) ? $hrd->id_hrd : $request->hrd;
            $employee->nama_lengkap = $request->name;
            $employee->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $employee->jenis_kelamin = $request->gender;
            $employee->email = $request->email;
            $employee->nomor_hp = $request->phone_number;
            $employee->posisi = Auth::user()->role == role('hrd') ? $request->position : 0;
            $employee->kantor = Auth::user()->role == role('hrd') ? $request->office : 0;
            $employee->nik_cis = '';
            $employee->nik = $request->identity_number != '' ? $request->identity_number : '';
            $employee->alamat = $request->address != '' ? $request->address : '';
            $employee->pendidikan_terakhir = $request->latest_education != '' ? $request->latest_education : '';
            $employee->awal_bekerja = $request->start_date != '' ? generate_date_format($request->start_date, 'y-m-d') : null;
            $employee->save();

            // Redirect
            return redirect()->route('admin.employee.index')->with(['message' => 'Berhasil menambah data.']);
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
            $employee = Karyawan::findOrFail($id);
        else {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->firstOrFail();
            $employee = Karyawan::where('id_karyawan','=',$id)->where('id_hrd','=',$hrd->id_hrd)->firstOrFail();
        }

        if($employee) {
            $employee->user = User::find($employee->id_user);
            $employee->kantor = Kantor::find($employee->kantor);
            $employee->posisi = Posisi::find($employee->posisi);
        }

        // View
        return view('admin/employee/detail', [
            'employee' => $employee
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

        // Get the employee
    	if(Auth::user()->role == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->firstOrFail();
            $employee = Karyawan::join('users','karyawan.id_user','=','users.id_user')->where('id_karyawan','=',$id)->where('id_hrd','=',$hrd->id_hrd)->firstOrFail();
        }
        else {
            $employee = Karyawan::join('users','karyawan.id_user','=','users.id_user')->findOrFail($id);
        }

        // Get positions and offices
        if(Auth::user()->role == role('admin')){
            $hrd = HRD::find($employee->id_hrd);
            $offices = Kantor::where('id_hrd','=',$hrd->id_hrd)->get();
            $positions = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
        }
        elseif(Auth::user()->role == role('hrd')){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $offices = Kantor::where('id_hrd','=',$hrd->id_hrd)->get();
            $positions = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
        }

        // View
        return view('admin/employee/edit', [
            'employee' => $employee,
            'offices' => $offices,
            'positions' => $positions
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
            'name' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'status' => 'required',
            'office' => Auth::user()->role == role('hrd') ? 'required' : '',
            'position' => Auth::user()->role == role('hrd') ? 'required' : '',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the employee
            $employee = Karyawan::find($request->id);
            $employee->nama_lengkap = $request->name;
            $employee->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $employee->jenis_kelamin = $request->gender;
            $employee->email = $request->email;
            $employee->nomor_hp = $request->phone_number;
            $employee->posisi = Auth::user()->role == role('hrd') ? $request->position : 0;
            $employee->kantor = Auth::user()->role == role('hrd') ? $request->office : 0;
            $employee->nik = $request->identity_number != '' ? $request->identity_number : '';
            $employee->alamat = $request->address != '' ? $request->address : '';
            $employee->pendidikan_terakhir = $request->latest_education != '' ? $request->latest_education : '';
            $employee->awal_bekerja = $request->start_date != '' ? generate_date_format($request->start_date, 'y-m-d') : null;
            $employee->save();

            // Update the user
            $user = User::find($employee->id_user);
            $user->nama_user = $request->name;
            $user->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $user->jenis_kelamin = $request->gender;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->save();

            // Redirect
            return redirect()->route('admin.employee.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the employee
        $employee = Karyawan::find($request->id);

        // Delete the employee
        $employee->delete();
        
        // Get the user
        $user = User::find($employee->id_user);

        // Delete the user
        $user->delete();

        // Get the applicant
        $applicant = Pelamar::where('id_user','=',$employee->id_user)->first();

        // Delete the applicant
        if($applicant) $applicant->delete();

        // Redirect
        return redirect()->route('admin.employee.index')->with(['message' => 'Berhasil menghapus data.']);
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

            // Get employees
            $employees = $hrd ? Karyawan::where('id_hrd','=',$hrd->id_hrd)->get() : Karyawan::get();

            // File name
            $filename = $hrd ? 'Data Karyawan '.$hrd->perusahaan.' ('.date('Y-m-d-H-i-s').')' : 'Data Semua Karyawan ('.date('d-m-Y-H-i-s').')';

            return Excel::download(new KaryawanExport($employees), $filename.'.xlsx');
        }
        elseif(Auth::user()->role == role('hrd')) {
            // Get the HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();

            // Get employees
            $employees = Karyawan::where('id_hrd','=',$hrd->id_hrd)->get();

            // File name
            $filename = 'Data Karyawan '.$hrd->perusahaan.' ('.date('Y-m-d-H-i-s').')';

            return Excel::download(new KaryawanExport($employees), $filename.'.xlsx');
        }
    }
}
