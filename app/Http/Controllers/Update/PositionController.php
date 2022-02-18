<?php

namespace App\Http\Controllers\Update;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Posisi;
use App\Models\HRD;
use App\Models\Tes;

class PositionController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Get offices
        if(Auth::user()->role_id == role('admin')) {
            $hrd = HRD::find($request->query('hrd'));
            $positions = $hrd ? Posisi::join('hrd','posisi.id_hrd','=','hrd.id_hrd')->where('hrd.id_hrd','=',$hrd->id_hrd)->get() : Posisi::join('hrd','posisi.id_hrd','=','hrd.id_hrd')->get();
        }
        elseif(Auth::user()->role_id == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id)->first();
            $positions = Posisi::where('id_hrd','=',$hrd->id_hrd)->get();
        }

        // Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // View
        return view('admin/position/index', [
            'positions' => $positions,
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
        if(Auth::user()->role_id == role('admin')) {
    	    $tests = Tes::all();
        }
        elseif(Auth::user()->role_id == role('hrd')) {
            $user = HRD::where('id_user','=',Auth::user()->id)->first();
            $ids = explode(',', $user->akses_tes);
            $tests = Tes::whereIn('id_tes',$ids)->get();
        }

        // View
        return view('admin/position/create', [
            'hrds' => $hrds,
            'tests' => $tests
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
    	// Get data HRD
    	if(Auth::user()->role_id == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id)->first();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'hrd' => Auth::user()->role_id == role('admin') ? 'required' : '',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the position
            $position = new Posisi;
            $position->id_hrd = isset($hrd) ? $hrd->id_hrd : $request->hrd;
            $position->nama_posisi = $request->name;
            $position->tes = !empty($request->get('tests')) ? implode(',', array_filter($request->get('tests'))) : '';
            $position->keahlian = !empty($request->get('skills')) ? implode(',', array_filter($request->get('skills'))) : '';
            $position->save();

            // Redirect
            return redirect()->route('admin.position.index')->with(['message' => 'Berhasil menambah data.']);
        }
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

        // Get the position
    	if(Auth::user()->role_id == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id)->firstOrFail();
            $position = Posisi::where('id_posisi','=',$id)->where('id_hrd','=',$hrd->id_hrd)->firstOrFail();
        }
        else {
            $position = Posisi::findOrFail($id);
        }

        // Set position tests and skills
        if($position) {
        	$position->tes = $position->tes != '' ? explode(',', $position->tes) : array();
            $position->keahlian = $position->keahlian != '' ? explode(',', $position->keahlian) : array();
        }

        // Get tests
    	$tests = get_perusahaan_tes($position->id_hrd);

        // View
        return view('admin/position/edit', [
            'position' => $position,
            'tests' => $tests
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
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the position
            $position = Posisi::find($request->id);
            $position->nama_posisi = $request->name;
            $position->tes = !empty($request->get('tests')) ? implode(',', array_filter($request->get('tests'))) : '';
            $position->keahlian = !empty($request->get('skills')) ? implode(',', array_filter($request->get('skills'))) : '';
            $position->save();

            // Redirect
            return redirect()->route('admin.position.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the position
        $position = Posisi::find($request->id);

        // Delete the position
        $position->delete();

        // Redirect
        return redirect()->route('admin.position.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}