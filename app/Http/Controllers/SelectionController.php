<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\Selection;
use App\Models\Company;
use App\Models\Office;
use App\Models\User;

class SelectionController extends \App\Http\Controllers\Controller
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

        // Get the company and selections
        if(Auth::user()->role->is_global === 1) {
            if($request->query('company') != null && $request->query('result') != null) {
                $company = Company::find($request->query('company'));

                if($company && in_array($request->query('result'), [1,2,0,99]))
                    $selections = Selection::has('user')->has('company')->has('vacancy')->where('company_id','=',$company->id)->where('status','=',$request->query('result'))->orderBy('test_time','desc')->get();
                elseif($company && !in_array($request->query('result'), [1,2,0,99]))
                    $selections = Selection::has('user')->has('company')->has('vacancy')->where('company_id','=',$company->id)->orderBy('test_time','desc')->get();
                elseif(!$company && in_array($request->query('result'), [1,2,0,99]))
                    $selections = Selection::has('user')->has('company')->has('vacancy')->where('status','=',$request->query('result'))->orderBy('test_time','desc')->get();
                else
                    $selections = Selection::has('user')->has('company')->has('vacancy')->orderBy('test_time','desc')->get();
            }
            else {
                $selections = Selection::has('user')->has('company')->has('vacancy')->orderBy('test_time','desc')->get();
            }
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
			
            if($request->query('result') != null && in_array($request->query('result'), [1,2,0,99]))
                $selections = Selection::has('user')->has('company')->has('vacancy')->where('company_id','=',$company->id)->where('status','=',$request->query('result'))->orderBy('test_time','desc')->get();
            else
                $selections = Selection::has('user')->has('company')->has('vacancy')->where('company_id','=',$company->id)->orderBy('test_time','desc')->get();
        }

        // Set selections
        if(count($selections) > 0) {
            foreach($selections as $key=>$selection) {
                $employee = User::where('role_id','=',role('employee'))->find($selection->user_id);
                $selections[$key]->isEmployee = !$employee ? false : true;
            }
        }

        // Get companies
        $companies = Company::orderBy('name','asc')->get();

    	// View
        return view('admin/selection/index', [
            'selections' => $selections,
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
    	if(Auth::user()->role->is_global === 1) {
            $applicant = User::find($request->user_id);
            if($applicant) $company = $user->attribute->company;
        }
    	elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'time' => 'required',
            'place' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Check selection
            $check = Selection::where('user_id','=',$request->user_id)->where('vacancy_id','=',$request->vacancy_id)->first();

            // If check is exist
            if($check) {
                return redirect()->route('admin.applicant.detail', ['id' => $request->user_id])->with(['message' => 'Sudah masuk ke data seleksi.']);
            }

            // Save the selection
            $selection = new Selection;
            $selection->company_id = $company->id;
            $selection->user_id = $request->user_id;
            $selection->vacancy_id = $request->vacancy_id;
            $selection->status = 99;
            $selection->test_time = DateTimeExt::change($request->date)." ".$request->time.":00";
            $selection->test_place = $request->place;
            $selection->save();

            // Redirect
            return redirect()->route('admin.selection.index')->with(['message' => 'Berhasil menambah data.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {        
        if($request->ajax()) {
            // Get the selection
            $selection = Selection::find($request->id);
            $selection->test_date = date('d/m/Y', strtotime($selection->test_time));

            return response()->json($selection, 200);
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
            'result' => 'required',
            'date' => $request->result == 99 ? 'required' : '',
            'time' => $request->result == 99 ? 'required' : '',
            'place' => $request->result == 99 ? 'required' : '',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the selection
            $selection = Selection::find($request->id);
            $selection->test_time = $request->result == 99 ? DateTimeExt::change($request->date)." ".$request->time.":00" : $selection->test_time;
            $selection->test_place = $request->result == 99 ? $request->place : $selection->test_place;
            $selection->status = $request->result;
            $selection->save();

            // Redirect
            return redirect()->route('admin.selection.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the selection
        $selection = Selection::find($request->id);

        // Delete the selection
        $selection->delete();

        // Redirect
        return redirect()->route('admin.selection.index')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Convert the applicant to employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function convert(Request $request)
    {
        // Check the access
        has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the selection
        $selection = Selection::has('user')->find($request->id);

        // Update the user role
        $selection->user->role_id = role('employee');
        $selection->user->save();

        // Redirect
        return redirect()->route('admin.selection.index')->with(['message' => 'Berhasil mengonversi data.']);
    }
}
