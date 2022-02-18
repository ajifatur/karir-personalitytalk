<?php

namespace App\Http\Controllers\Update;

use Auth;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Stifin;
use App\Models\StifinAim;
use App\Models\StifinTest;
use App\Models\HRD;

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
        if(!stifin_access()) abort(403);

        if(Auth::user()->role_id == role('admin')) {
    	    // Get the STIFIns
			$stifins = Stifin::all();

			// View
			return view('admin/stifin/index', [
				'stifins' => $stifins,
			]);
        }
        elseif(Auth::user()->role_id == role('hrd')) {
			// Get the HRD
			$hrd = HRD::where('id_user','=',Auth::user()->id)->firstOrFail();
			
			// Get the STIFIns
			$stifins = Stifin::where('hrd_id','=',$hrd->id_hrd)->get();

			// View
			return view('admin/stifin/index', [
				'hrd' => $hrd,
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
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Check the access
        if(!stifin_access()) abort(403);

    	// Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // Get STIFIn types
        $types = StifinTest::all();
		
        // Get STIFIn aims
        $aims = StifinAim::all();

        if(Auth::user()->role_id == role('admin')) {
            // View
            return view('admin/stifin/create', [
                'hrds' => $hrds,
                'types' => $types,
                'aims' => $aims,
            ]);
        }
        elseif(Auth::user()->role_id == role('hrd')) {
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
    	// Get the HRD
    	if(Auth::user()->role_id == role('admin')) {
            $hrd = HRD::find($request->hrd);
        }
    	elseif(Auth::user()->role_id == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id)->first();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required',
            'hrd' => Auth::user()->role_id == role('admin') ? 'required' : '',
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
            $stifin->name = $request->name;
            $stifin->gender = $request->gender;
            $stifin->test = $request->type;
            $stifin->aim = $request->aim;
            $stifin->hrd_id = isset($hrd) ? $hrd->id_hrd : $request->hrd;
            $stifin->birthdate = $request->birthdate != '' ? generate_date_format($request->birthdate, 'y-m-d') : null;
            $stifin->test_at = $request->test_at != '' ? generate_date_format($request->test_at, 'y-m-d') : null;
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
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Check the access
        if(!stifin_access()) abort(403);

        // Get the STIFIn
        $stifin = Stifin::findOrFail($id);

        // Get STIFIn types
        $types = StifinTest::all();
		
        // Get STIFIn aims
        $aims = StifinAim::all();

        if(Auth::user()->role_id == role('admin')) {
            // View
            return view('admin/stifin/edit', [
                'stifin' => $stifin,
                'types' => $types,
                'aims' => $aims,
            ]);
        }
        elseif(Auth::user()->role_id == role('hrd')) {
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
            $stifin->name = $request->name;
            $stifin->gender = $request->gender;
            $stifin->test = $request->type;
            $stifin->aim = $request->aim;
            $stifin->birthdate = $request->birthdate != '' ? generate_date_format($request->birthdate, 'y-m-d') : null;
            $stifin->test_at = $request->test_at != '' ? generate_date_format($request->test_at, 'y-m-d') : null;
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
        // has_access(method(__METHOD__), Auth::user()->role_id);
        
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
        if(!stifin_access()) abort(403);

        // Get the STIFIn
        $stifin = Stifin::join('stifin_tests','stifin.test','=','stifin_tests.id_st')->findOrFail($id);

        // View
        if(Auth::user()->role_id == role('admin')) {
			// PDF
			$pdf = PDF::loadview('admin/stifin/print/'.$stifin->test_code, [
                'stifin' => $stifin,
			]);
			$pdf->setPaper('A4', 'portrait');

			return $pdf->stream("STIFIn-".$stifin->name.".pdf");
        }
        elseif(Auth::user()->role_id == role('hrd')) {
			// PDF
			$pdf = PDF::loadview('admin/stifin/print/'.$stifin->test_code, [
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