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
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'username' => 'required|string|min:4|unique:users',
            'password' => 'required|min:4',
            'code' => 'required|alpha|min:3|max:4',
            'company_name' => 'required',
            'stifin' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the user
            $user = new User;
            $user->nama_user = $request->name;
            $user->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $user->jenis_kelamin = $request->gender;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->foto = '';
            $user->role = role('hrd');
            $user->has_access = 1;
            $user->status = 1;
            $user->last_visit = null;
            $user->created_at = date("Y-m-d H:i:s");
            $user->save();

            // Save the HRD
            $hrd = new HRD;
            $hrd->id_user = $user->id_user;
            $hrd->nama_lengkap = $request->name;
            $hrd->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $hrd->jenis_kelamin = $request->gender;
            $hrd->email = $request->email;
            $hrd->kode = $request->code;
            $hrd->perusahaan = $request->company_name;
            $hrd->alamat_perusahaan = $request->company_address != '' ? $request->company_address : '';
            $hrd->telepon_perusahaan = $request->company_phone != '' ? $request->company_phone : '';
            $hrd->akses_tes = !empty($request->get('tests')) ? implode(',', array_filter($request->get('tests'))) : '';
            $hrd->akses_stifin = $request->stifin;
            $hrd->save();

            // Save the Head Office
            $kantor = new Kantor;
			$kantor->id_hrd = $hrd->id_hrd;
			$kantor->nama_kantor = 'Head Office';
			$kantor->alamat_kantor = $request->company_address != '' ? $request->company_address : '';
			$kantor->telepon_kantor = $request->company_phone != '' ? $request->company_phone : '';
			$kantor->save();

            // Redirect
            return redirect()->route('admin.hrd.index')->with(['message' => 'Berhasil menambah data.']);
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

        if(Auth::user()->role == role('admin')) {
            // Get the HRD
            $hrd = HRD::findOrFail($id);
            $hrd->user = User::find($hrd->id_user);

            // View
            return view('admin/hrd/detail', [
                'hrd' => $hrd
            ]);
        }
        else abort(403);
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

        if(Auth::user()->role == role('admin')) {
            // Get the HRD
            $hrd = HRD::findOrFail($id);
            $hrd->akses_tes = $hrd->akses_tes != '' ? explode(',', $hrd->akses_tes) : [];

            // Get the user
            $user = User::findOrFail($hrd->id_user);

            // Get tests
    	    $tests = Tes::all();

            // View
            return view('admin/hrd/edit', [
                'hrd' => $hrd,
                'user' => $user,
                'tests' => $tests
            ]);
        }
        else abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Get the HRD
        $hrd = HRD::find($request->id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($hrd->id_user, 'id_user'),
            ],
            'username' => [
                'required', 'string', 'min:4',
                Rule::unique('users')->ignore($hrd->id_user, 'id_user'),
            ],
            'password' => $request->password != '' ? 'required|min:4' : '',
            'code' => [
                'required', 'alpha', 'min:3', 'max:4',
                // Rule::unique('hrd')->ignore($hrd->id_hrd, 'id_hrd'),
            ],
            'company_name' => 'required',
            'stifin' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the user
            $user = User::find($hrd->id_user);
            $user->nama_user = $request->name;
            $user->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $user->jenis_kelamin = $request->gender;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = $request->password != '' ? bcrypt($request->password) : $user->password;
            $user->save();

            // Update the HRD
            $hrd->nama_lengkap = $request->name;
            $hrd->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $hrd->jenis_kelamin = $request->gender;
            $hrd->email = $request->email;
            $hrd->kode = $request->code;
            $hrd->perusahaan = $request->company_name;
            $hrd->alamat_perusahaan = $request->company_address != '' ? $request->company_address : '';
            $hrd->telepon_perusahaan = $request->company_phone != '' ? $request->company_phone : '';
            $hrd->akses_tes = !empty($request->get('tests')) ? implode(',', array_filter($request->get('tests'))) : '';
            $hrd->akses_stifin = $request->stifin;
            $hrd->save();

            // Update the Head Office
            $kantor = Kantor::where('id_hrd','=',$hrd->id_hrd)->where('nama_kantor','=','Head Office')->first();
			$kantor->alamat_kantor = $request->company_address != '' ? $request->company_address : '';
			$kantor->telepon_kantor = $request->company_phone != '' ? $request->company_phone : '';
			$kantor->save();

            // Redirect
            return redirect()->route('admin.hrd.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the HRD
        $hrd = HRD::find($request->id);

        // Delete the HRD
        $hrd->delete();
        
        // Get the user
        $user = User::find($hrd->id_user);

        // Delete the user
        $user->delete();

        // Redirect
        return redirect()->route('admin.hrd.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}