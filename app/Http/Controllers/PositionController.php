<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Position;
use App\Models\Company;
use App\Models\Test;
use App\Models\Skill;

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
        has_access(method(__METHOD__), Auth::user()->role_id);

        // Get offices
        if(Auth::user()->role->is_global === 1) {
            $company = Company::find($request->query('company'));
            $positions = $company ? $company->positions()->has('role')->orderBy('name','asc')->get() : Position::has('company')->has('role')->orderBy('company_id','asc')->orderBy('name','asc')->get();
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $positions = $company ? $company->positions()->has('role')->orderBy('name','asc')->get() : [];
        }

        // Get companies
        $companies = Company::orderBy('name','asc')->get();

        // View
        return view('admin/position/index', [
            'positions' => $positions,
            'companies' => $companies
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

        // Get companies
        $companies = Company::orderBy('name','asc')->get();

        // Get tests
        if(Auth::user()->role->is_global === 1) {
    	    $tests = Test::all();
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $tests = $company ? $company->tests : [];
        }

        // View
        return view('admin/position/create', [
            'companies' => $companies,
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
    	// Get the company
    	if(Auth::user()->role_id == role('hrd')) {
            $company = Company::find(Auth::user()->attribute->company_id);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'company' => Auth::user()->role->is_global === 1 ? 'required' : '',
            'role' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the position
            $position = new Position;
            $position->company_id = isset($company) ? $company->id : $request->company;
            $position->role_id = $request->role;
            $position->name = $request->name;
            $position->save();

            // Save position tests
            if(count($request->tests) > 0) {
                $position->tests()->attach($request->tests);
            }

            // Save position skills
            if(count($request->skills) > 0) {
                foreach($request->skills as $s) {
					if($s != null) {
						$skill = Skill::firstOrCreate(['name' => $s]);
						if($skill) $position->skills()->attach($skill->id);
					}
                }
            }

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
        has_access(method(__METHOD__), Auth::user()->role_id);

        // Get the position and tests
    	if(Auth::user()->role->is_global === 1) {
            $position = Position::has('company')->has('role')->findOrFail($id);
    	    $tests = Test::all();
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $position = Position::has('role')->where('company_id','=',$company->id)->findOrFail($id);
            $tests = $company ? $company->tests : [];
        }

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
            'role' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the position
            $position = Position::find($request->id);
            $position->role_id = $request->role;
            $position->name = $request->name;
            $position->save();

            // Update position tests
            if(count($request->tests) > 0) {
                $position->tests()->sync($request->tests);
            }

            // Update position skills
            if(count($request->skills) > 0) {
                $ids = [];
                foreach($request->skills as $s) {
					if($s != null) {
						$skill = Skill::firstOrCreate(['name' => $s]);
						if($skill) array_push($ids, $skill->id);
					}
                }
                $position->skills()->sync($ids);
            }

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
        has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the position
        $position = Position::find($request->id);

        // Delete the position
        $position->tests()->detach();
        $position->skills()->detach();
        $position->delete();

        // Redirect
        return redirect()->route('admin.position.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}
