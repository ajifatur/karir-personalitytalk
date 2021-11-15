<?php

namespace App\Http\Controllers\Update;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tes;
use App\Models\HRD;
use App\Models\PaketSoal;
use App\Models\TesSettings;

class TestController extends \App\Http\Controllers\Controller
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

        if(Auth::user()->role == role('admin')) {
    	    // Get the tests
			$tests = Tes::all();

            // View
        	return view('admin/test/index', [
        		'tests' => $tests,
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
            // View
        	return view('admin/test/create');
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
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Generate the permalink
            $permalink = generate_permalink($request->name);
            $i = 1;
            while(count_existing_data('tes', 'path', $permalink, 'id_tes', null) > 0){
                $permalink = rename_permalink(generate_permalink($request->name), $i);
                $i++;
            }

            // Save the test
            $test = new Tes;
            $test->nama_tes = $request->name;
            $test->path = $permalink;
            $test->waktu_tes = null;
            $test->save();

            // Redirect
            return redirect()->route('admin.test.index')->with(['message' => 'Berhasil menambah data.']);
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


        if(Auth::user()->role == role('admin')) {
            // Get the test
            $test = Tes::findOrFail($id);

            // View
        	return view('admin/test/edit', [
                'test' => $test
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
            // Generate the permalink
            $permalink = generate_permalink($request->name);
            $i = 1;
            while(count_existing_data('tes', 'path', $permalink, 'id_tes', $request->id) > 0){
                $permalink = rename_permalink(generate_permalink($request->name), $i);
                $i++;
            }

            // Update the test
            $test = Tes::find($request->id);
            $test->nama_tes = $request->name;
            $test->path = $permalink;
            $test->save();

            // Redirect
            return redirect()->route('admin.test.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the test
        $test = Tes::find($request->id);

        // Delete the test
        $test->delete();

        // Redirect
        return redirect()->route('admin.test.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}