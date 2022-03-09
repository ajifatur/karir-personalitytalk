<?php

namespace App\Http\Controllers;

use Auth;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\Stifin;
use App\Models\StifinAim;
use App\Models\StifinType;
use App\Models\Company;

class StifinController extends \App\Http\Controllers\Controller
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
        has_access(method(__METHOD__), Auth::user()->role_id);
        if(!stifin_access()) abort(403);

        if(Auth::user()->role->is_global === 1) {
    	    // Get the STIFIns
			$stifins = Stifin::all();

			// View
			return view('admin/stifin/index', [
				'stifins' => $stifins,
			]);
        }
        elseif(Auth::user()->role->is_global === 0) {
			// Get the company
            $company = Company::find(Auth::user()->attribute->company_id);
			
			// Get the STIFIns
			$stifins = $company ? Stifin::where('company_id','=',$company->id)->get() : [];

			// View
			return view('admin/stifin/index', [
				'stifins' => $stifins,
			]);
		}
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
        if(!stifin_access()) abort(403);

        // Get companies
        $companies = Company::orderBy('name','asc')->get();

        // Get STIFIn types
        $types = StifinType::all();
		
        // Get STIFIn aims
        $aims = StifinAim::all();

        if(Auth::user()->role->is_global === 1) {
            // View
            return view('admin/stifin/create', [
                'companies' => $companies,
                'types' => $types,
                'aims' => $aims,
            ]);
        }
        elseif(Auth::user()->role->is_global === 0) {
            // View
            return view('admin/stifin/create', [
                'types' => $types,
                'aims' => $aims,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	// Get the company
    	if(Auth::user()->role->is_global === 1)
            $company = Company::find($request->company);
    	elseif(Auth::user()->role->is_global === 0)
            $company = Company::find(Auth::user()->attribute->company_id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'company' => Auth::user()->role->is_global === 1 ? 'required' : '',
            'type' => 'required',
            'aim' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the STIFIn
            $stifin = new Stifin;
            $stifin->company_id = isset($company) ? $company->id : $request->company;
            $stifin->type_id = $request->type;
            $stifin->aim_id = $request->aim;
            $stifin->name = $request->name;
            $stifin->gender = $request->gender;
            $stifin->birthdate = $request->birthdate != '' ? DateTimeExt::change($request->birthdate) : null;
            $stifin->test_at = $request->test_at != '' ? DateTimeExt::change($request->test_at) : null;
            $stifin->save();

            // Redirect
            return redirect()->route('admin.stifin.index')->with(['message' => 'Berhasil menambah data.']);
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
        if(!stifin_access()) abort(403);

        // Get the STIFIn
        $stifin = Stifin::has('company')->has('type')->has('aim')->findOrFail($id);

        // Get STIFIn types
        $types = StifinType::all();
		
        // Get STIFIn aims
        $aims = StifinAim::all();

        if(Auth::user()->role->is_global === 1) {
            // View
            return view('admin/stifin/edit', [
                'stifin' => $stifin,
                'types' => $types,
                'aims' => $aims,
            ]);
        }
        elseif(Auth::user()->role->is_global === 0) {
            // View
            return view('admin/stifin/edit', [
                'stifin' => $stifin,
                'types' => $types,
                'aims' => $aims,
            ]);
        }
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
            'gender' => 'required',
            'type' => 'required',
            'aim' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the STIFIn
            $stifin = Stifin::find($request->id);
            $stifin->type_id = $request->type;
            $stifin->aim_id = $request->aim;
            $stifin->name = $request->name;
            $stifin->gender = $request->gender;
            $stifin->birthdate = $request->birthdate != '' ? DateTimeExt::change($request->birthdate) : null;
            $stifin->test_at = $request->test_at != '' ? DateTimeExt::change($request->test_at) : null;
            $stifin->save();

            // Redirect
            return redirect()->route('admin.stifin.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        if(!stifin_access()) abort(403);
        
        // Get the STIFIn
        $stifin = Stifin::find($request->id);

        // Delete the STIFIn
        $stifin->delete();

        // Redirect
        return redirect()->route('admin.stifin.index')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Print the specified resource in storage.
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        // Check the access
        has_access(method(__METHOD__), Auth::user()->role_id);
        if(!stifin_access()) abort(403);

        // Get the STIFIn
        $stifin = Stifin::has('company')->has('type')->has('aim')->findOrFail($id);

        // View
        if(Auth::user()->role->is_global === 1) {
			// PDF
			$pdf = PDF::loadview('admin/stifin/print/'.$stifin->type->code, [
                'stifin' => $stifin,
			]);
			$pdf->setPaper('A4', 'portrait');

			return $pdf->stream("STIFIn-".$stifin->name.".pdf");
        }
        elseif(Auth::user()->role->is_global === 0) {
			// PDF
			$pdf = PDF::loadview('admin/stifin/print/'.$stifin->type->code, [
                'stifin' => $stifin,
			]);
			$pdf->setPaper('A4', 'portrait');

			return $pdf->stream("STIFIn-".$stifin->name.".pdf");
        }
        else {
            abort(404);
        }
    }
}