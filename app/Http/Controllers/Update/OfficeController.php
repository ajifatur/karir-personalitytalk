<?php

namespace App\Http\Controllers\Update;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Kantor;
use App\Models\HRD;

class OfficeController extends \App\Http\Controllers\Controller
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
        has_access(method(__METHOD__), Auth::user()->role->id);

        // Get offices
        if(Auth::user()->role->is_global === 1) {
            $hrd = HRD::find($request->query('hrd'));
            $offices = $hrd ? Kantor::join('hrd','kantor.id_hrd','=','hrd.id_hrd')->where('kantor.id_hrd','=',$hrd->id_hrd)->get() : Kantor::join('hrd','kantor.id_hrd','=','hrd.id_hrd')->get();
        }
        elseif(Auth::user()->role->is_global === 0) {
            $hrd = HRD::where('id_user','=',Auth::user()->id)->first();
            $offices = Kantor::join('hrd','kantor.id_hrd','=','hrd.id_hrd')->where('kantor.id_hrd','=',$hrd->id_hrd)->get();
        }

        // Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // View
        return view('admin/office/index', [
            'offices' => $offices,
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
        has_access(method(__METHOD__), Auth::user()->role_id);

        // Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // View
        return view('admin/office/create', [
            'hrds' => $hrds
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
            'hrd' => Auth::user()->role->is_global === 1 ? 'required' : '',
            'phone_number' => $request->phone_number != '' ? 'numeric' : ''
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the office
            $office = new Kantor;
            $office->id_hrd = isset($hrd) ? $hrd->id_hrd : $request->hrd;
            $office->nama_kantor = $request->name;
            $office->alamat_kantor = $request->address != '' ? $request->address : '';
            $office->telepon_kantor = $request->phone_number != '' ? $request->phone_number : '';
            $office->save();

            // Redirect
            return redirect()->route('admin.office.index')->with(['message' => 'Berhasil menambah data.']);
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
        has_access(method(__METHOD__), Auth::user()->role_id);

        // Get the office
    	if(Auth::user()->role_id == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id)->first();
            $office = Kantor::where('id_kantor','=',$id)->where('id_hrd','=',$hrd->id_hrd)->firstOrFail();
        }
        else {
            $office = Kantor::findOrFail($id);
        }

        // View
        return view('admin/office/edit', [
            'office' => $office
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
            'phone_number' => $request->phone_number != '' ? 'numeric' : ''
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the office
            $office = Kantor::find($request->id);
            $office->nama_kantor = $request->name;
            $office->alamat_kantor = $request->address != '' ? $request->address : '';
            $office->telepon_kantor = $request->phone_number != '' ? $request->phone_number : '';
            $office->save();

            // Redirect
            return redirect()->route('admin.office.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the office
        $office = Kantor::find($request->id);

        // Delete the office
        $office->delete();

        // Redirect
        return redirect()->route('admin.office.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}