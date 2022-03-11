<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\User;
use App\Models\Company;
use App\Models\Test;
use App\Models\UserAttribute;
use App\Models\Office;

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
        // Check the access
        has_access(method(__METHOD__), Auth::user()->role_id);

        // Get HRDs
        $hrds = User::whereHas('attribute', function (Builder $query) {
            return $query->has('company');
        })->where('role_id','=',role('hrd'))->orderBy('last_visit','desc')->get();

        // View
        return view('admin/hrd/index', [
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

        // Get tests
        $tests = Test::orderBy('name','asc')->get();

        // View
        return view('admin/hrd/create', [
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
            $user->role_id = role('hrd');
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->access_token = null;
            $user->avatar = '';
            $user->status = 1;
            $user->last_visit = null;
            $user->save();

            // Save the company
            $company = new Company;
            $company->user_id = $user->id;
            $company->name = $request->company_name;
            $company->code = $request->code;
            $company->address = $request->company_address != '' ? $request->company_address : '';
            $company->phone_number = $request->company_phone != '' ? $request->company_phone : '';
            $company->stifin = $request->stifin;
            $company->save();

            // Save the user attributes
            $user_attribute = new UserAttribute;
            $user_attribute->user_id = $user->id;
            $user_attribute->company_id = $company->id;
            $user_attribute->office_id = 0;
            $user_attribute->position_id = 0;
            $user_attribute->vacancy_id = 0;
            $user_attribute->birthdate = DateTimeExt::change($request->birthdate);
            $user_attribute->birthplace = '';
            $user_attribute->gender = $request->gender;
            $user_attribute->country_code = 'ID';
            $user_attribute->dial_code = '+62';
            $user_attribute->phone_number = $request->phone_number;
            $user_attribute->address = '';
            $user_attribute->identity_number = '';
            $user_attribute->religion = 0;
            $user_attribute->relationship = 0;
            $user_attribute->latest_education = '';
            $user_attribute->job_experience = '';
            $user_attribute->start_date = null;
            $user_attribute->end_date = null;
            $user_attribute->save();

            // Save company tests
            if(count($request->tests) > 0)
                $company->tests()->sync($request->tests);

            // Save the Head Office
            $office = new Office;
            $office->company_id = $company->id;
            $office->name = 'Head Office';
            $office->address = $request->company_address != '' ? $request->company_address : '';
            $office->phone_number = $request->company_phone != '' ? $request->company_phone : '';
            $office->is_main = 1;
            $office->save();

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
        has_access(method(__METHOD__), Auth::user()->role_id);

        // Get the HRD
        $hrd = User::whereHas('attribute', function (Builder $query) {
            return $query->has('company');
        })->where('role_id','=',role('hrd'))->findOrFail($id);

        // View
        return view('admin/hrd/detail', [
            'hrd' => $hrd
        ]);
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

        // Get the HRD
        $hrd = User::whereHas('attribute', function (Builder $query) {
            return $query->has('company');
        })->where('role_id','=',role('hrd'))->findOrFail($id);

        // Get tests
        $tests = Test::orderBy('name','asc')->get();

        // View
        return view('admin/hrd/edit', [
            'hrd' => $hrd,
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
        // Get the HRD
        $hrd = User::where('role_id','=',role('hrd'))->find($request->id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($hrd->id, 'id'),
            ],
            'username' => [
                'required', 'string', 'min:4',
                Rule::unique('users')->ignore($hrd->id, 'id'),
            ],
            'password' => $request->password != '' ? 'required|min:4' : '',
            'code' => [
                'required', 'alpha', 'min:3', 'max:4',
                Rule::unique('companies')->ignore($hrd->attribute->company_id, 'id'),
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
            $user = $hrd;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = $request->password != '' ? bcrypt($request->password) : $user->password;
            $user->save();

            // Update the user attribute
            $user->attribute->birthdate = DateTimeExt::change($request->birthdate);
            $user->attribute->gender = $request->gender;
            $user->attribute->phone_number = $request->phone_number;
            $user->attribute->save();

            // Update the company
            $company = Company::find($user->attribute->company_id);
            $company->name = $request->company_name;
            $company->code = $request->code;
            $company->address = $request->company_address != '' ? $request->company_address : '';
            $company->phone_number = $request->company_phone != '' ? $request->company_phone : '';
            $company->stifin = $request->stifin;
            $company->save();

            // Update company tests
            if(count($request->tests) > 0)
                $company->tests()->sync($request->tests);

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
        has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the HRD
        $hrd = User::has('attribute')->find($request->id);

        // Delete the HRD
        $hrd->delete();
        
        // Get the company
        $company = Company::find($hrd->attribute->company_id);

        // Delete the user
        $company->delete();

        // Redirect
        return redirect()->route('admin.hrd.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}