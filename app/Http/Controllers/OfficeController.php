<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Office;
use App\Models\Company;

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
            $company = Company::find($request->query('company'));
            $offices = $company ? $company->offices()->orderBy('is_main','desc')->orderBy('name','asc')->get() : Office::has('company')->orderBy('is_main','desc')->orderBy('name','asc')->get();
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $offices = $company ? $company->offices()->orderBy('is_main','desc')->orderBy('name','asc')->get() : [];
        }

        // Get companies
        $companies = Company::orderBy('name','asc')->get();

        // View
        return view('admin/office/index', [
            'offices' => $offices,
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

        // View
        return view('admin/office/create', [
            'companies' => $companies
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
            'phone_number' => $request->phone_number != '' ? 'numeric' : '',
            'is_main' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the office
            $office = new Office;
            $office->company_id = isset($company) ? $company->id : $request->company;
            $office->name = $request->name;
            $office->address = $request->address != '' ? $request->address : '';
            $office->phone_number = $request->phone_number != '' ? $request->phone_number : '';
            $office->is_main = $request->is_main;
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
    	if(Auth::user()->role->is_global === 1) {
            $office = Office::has('company')->findOrFail($id);
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $office = Office::where('company_id','=',$company->id)->findOrFail($id);
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
            'phone_number' => $request->phone_number != '' ? 'numeric' : '',
            'is_main' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the office
            $office = Office::find($request->id);
            $office->name = $request->name;
            $office->address = $request->address != '' ? $request->address : '';
            $office->phone_number = $request->phone_number != '' ? $request->phone_number : '';
            $office->is_main = $request->is_main;
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
        $office = Office::find($request->id);

        // Delete the office
        $office->delete();

        // Redirect
        return redirect()->route('admin.office.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}