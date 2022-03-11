<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use App\Models\Position;

class PositionTestController extends \App\Http\Controllers\Controller
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

        // Get the company
        if(Auth::user()->role->is_global === 1) {
            $company = Company::find($request->query('company'));
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
        }
            
        // Get tests
        $tests = $company ? $company->tests : [];

        // Get positions
        $positions =  $company ? $company->positions()->has('role')->orderBy('name','asc')->get() : [];

        // Get companies
        $companies = Company::orderBy('name','asc')->get();

        // View
        return view('admin/position-test/index', [
            'company' => $company,
            'companies' => $companies,
            'tests' => $tests,
            'positions' => $positions,
        ]);
    }

    /**
     * Change the resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        // Get the position
        $position = Position::find($request->position);

        if($position) {
            // Add to position if true
            if($request->isChecked == 1)
                $position->tests()->attach($request->test);
            // Remove from position if false
            else
                $position->tests()->detach($request->test);

            echo 'Berhasil mengganti status.';
        }
        else
            echo 'Tidak dapat mengganti status.';
    }
}