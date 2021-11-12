<?php

namespace App\Http\Controllers\Update;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\HRD;
use App\Models\Kantor;
use App\Models\Tes;
use App\Models\User;

class HRDController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->role == role('admin')) {
            // Get HRDs
            $hrds = HRD::join('users','hrd.id_user','=','users.id_user')->get();

            // View
            return view('admin/hrd/index', [
                'hrds' => $hrds
            ]);
        }
        else abort(403);
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

        if(Auth::user()->role == role('admin')) {
            // Get tests
    	    $tests = Tes::all();

            // View
            return view('admin/hrd/create', [
                'tests' => $tests
            ]);
        }
        else abort(403);
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
            $user = User::find($employee->id_karyawan);
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
}