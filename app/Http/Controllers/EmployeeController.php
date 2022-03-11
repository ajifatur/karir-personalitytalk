<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Ajifatur\Helpers\DateTimeExt;
use App\Exports\EmployeeExport;
// use App\Imports\KaryawanImport;
use App\Models\Company;
use App\Models\User;
use App\Models\UserAttribute;
use App\Models\Office;
use App\Models\Position;

class EmployeeController extends \App\Http\Controllers\Controller
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

        if($request->ajax()) {
            // Get employees
            if(Auth::user()->role->is_global === 1) {
                $company = Company::find($request->query('company'));
                if($company) {
                    $employees = User::whereHas('attribute', function (Builder $query) use ($company) {
                        return $query->has('company')->where('company_id','=',$company->id);
                    })->where('role_id','=',role('employee'))->get();
                }
                else {
                    $employees = User::whereHas('attribute', function (Builder $query) {
                        return $query->has('company');
                    })->where('role_id','=',role('employee'))->get();
                }
            }
            elseif(Auth::user()->role->is_global === 0) {
                $company = Company::find(Auth::user()->attribute->company_id);
                $employees = User::whereHas('attribute', function (Builder $query) use ($company) {
                    return $query->has('company')->where('company_id','=',$company->id);
                })->where('role_id','=',role('employee'))->get();
            }

            // Set
            if(count($employees) > 0) {
                foreach($employees as $key=>$employee) {
                    $employees[$key]->phone_number = $employee->attribute->phone_number;
                    $employees[$key]->company_name = $employee->attribute->company->name;
                    $employees[$key]->position_name = $employee->attribute->position ? $employee->attribute->position->name : '-';
                }
            }

            // Return
            return DataTables::of($employees)
                ->addColumn('checkbox', '<input type="checkbox" class="form-check-input checkbox-one">')
                ->editColumn('name', '
                    <span class="d-none">{{ $name }}</span>
                    <a href="{{ route(\'admin.employee.detail\', [\'id\' => $id]) }}">{{ ucwords($name) }}</a>
                    <br>
                    <small class="text-muted"><i class="bi-envelope me-2"></i>{{ $email }}</small>
                    <br>
                    <small class="text-muted"><i class="bi-phone me-2"></i>{{ $phone_number }}</small>
                ')
                ->editColumn('status', '
                    <span class="badge {{ $status == 1 ? "bg-success" : "bg-danger" }}">{{ status($status) }}</span>
                ')
                ->addColumn('options', '
                    <div class="btn-group">
                        <a href="{{ route(\'admin.employee.detail\', [\'id\' => $id]) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Lihat Detail"><i class="bi-eye"></i></a>
                        <a href="{{ route(\'admin.employee.edit\', [\'id\' => $id]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                    </div>
                ')
                ->addColumn('datetime', '
                    <span class="d-none">{{ $created_at != null ? $created_at : "" }}</span>
                    {{ $created_at != null ? date("d/m/Y", strtotime($created_at)) : "-" }}
                    <br>
                    <small class="text-muted">{{ date("H:i", strtotime($created_at))." WIB" }}</small>
                ')
                ->rawColumns(['checkbox', 'name', 'username', 'status', 'datetime', 'options'])
                ->make(true);
        }

        // Get companies
        $companies = Company::orderBy('name','asc')->get();

        // View
        return view('admin/employee/index', [
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

        // Get offices and positions
        if(Auth::user()->role->is_global === 1) {
            $offices = [];
            $positions = Position::orderBy('name','asc')->get();
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $offices = $company ? $company->offices()->orderBy('is_main','desc')->orderBy('name','asc')->get() : [];
            $positions = $company ? $company->positions()->has('role')->orderBy('name','asc')->get() : [];
        }

        // View
        return view('admin/employee/create', [
            'companies' => $companies,
            'positions' => $positions,
            'offices' => $offices,
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
            $company = Company::find($request->company);
        }
    	elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'status' => 'required',
            'company' => Auth::user()->role->is_global === 1 ? 'required' : '',
            'office' => Auth::user()->role->is_global === 0 ? 'required' : '',
            'position' => Auth::user()->role->is_global === 0 ? 'required' : '',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Generate username
            $data_user = User::whereHas('attribute', function (Builder $query) use ($company) {
                return $query->has('company')->where('company_id','=',$company->id);
            })->where('username','like',$company->code.'%')->latest('username')->first();
            if(!$data_user)
                $username = generate_username(null, $company->code);
            else
                $username = generate_username($data_user->username, $company->code);

            // Save the user
            $user = new User;
            $user->role_id = role('employee');
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $username;
            $user->password = bcrypt($username);
            $user->access_token = null;
            $user->avatar = '';
            $user->status = $request->status;
            $user->last_visit = null;
            $user->save();

            // Save the user attributes
            $user_attribute = new UserAttribute;
            $user_attribute->user_id = $user->id;
            $user_attribute->company_id = $company->id;
            $user_attribute->office_id = Auth::user()->role->is_global === 0 ? $request->office : 0;
            $user_attribute->position_id = Auth::user()->role->is_global === 0 ? $request->position : 0;
            $user_attribute->vacancy_id = 0;
            $user_attribute->birthdate = DateTimeExt::change($request->birthdate);
            $user_attribute->birthplace = '';
            $user_attribute->gender = $request->gender;
            $user_attribute->country_code = 'ID';
            $user_attribute->dial_code = '+62';
            $user_attribute->phone_number = $request->phone_number;
            $user_attribute->address = $request->address != '' ? $request->address : '';
            $user_attribute->identity_number = $request->identity_number != '' ? $request->identity_number : '';
            $user_attribute->religion = 0;
            $user_attribute->relationship = 0;
            $user_attribute->latest_education = $request->latest_education != '' ? $request->latest_education : '';
            $user_attribute->job_experience = $request->job_experience != '' ? $request->job_experience : '';
            $user_attribute->start_date = $request->start_date != '' ? DateTimeExt::change($request->start_date) : null;
            $user_attribute->end_date = null;
            $user_attribute->save();

            // Redirect
            return redirect()->route('admin.employee.index')->with(['message' => 'Berhasil menambah data.']);
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

        // Get the employee
    	if(Auth::user()->role->is_global === 1) {
            $employee = User::whereHas('attribute', function (Builder $query) {
                return $query->has('company');
            })->where('role_id','=',role('employee'))->findOrFail($id);
        }
    	if(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $employee = User::whereHas('attribute', function (Builder $query) use ($company) {
                return $query->has('company')->where('company_id','=',$company->id);
            })->where('role_id','=',role('employee'))->findOrFail($id);
        }

        // View
        return view('admin/employee/detail', [
            'employee' => $employee
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

        // Get the employee
    	if(Auth::user()->role->is_global === 1) {
            $employee = User::whereHas('attribute', function (Builder $query) {
                return $query->has('company');
            })->where('role_id','=',role('employee'))->findOrFail($id);
        }
    	if(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $employee = User::whereHas('attribute', function (Builder $query) use ($company) {
                return $query->has('company')->where('company_id','=',$company->id);
            })->where('role_id','=',role('employee'))->findOrFail($id);
        }

        // Get offices and positions
        $offices = Office::has('company')->where('company_id','=',$employee->attribute->company_id)->orderBy('is_main','desc')->orderBy('name','asc')->get();
        $positions = Position::has('company')->has('role')->where('company_id','=',$employee->attribute->company_id)->orderBy('name','asc')->get();

        // View
        return view('admin/employee/edit', [
            'employee' => $employee,
            'offices' => $offices,
            'positions' => $positions
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
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'status' => 'required',
            'office' => 'required',
            'position' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the user
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->save();

            // Update the user attribute
            $user->attribute->office_id = $request->office;
            $user->attribute->position_id = $request->position;
            $user->attribute->birthdate = DateTimeExt::change($request->birthdate);
            $user->attribute->gender = $request->gender;
            $user->attribute->phone_number = $request->phone_number;
            $user->attribute->address = $request->address != '' ? $request->address : '';
            $user->attribute->identity_number = $request->identity_number != '' ? $request->identity_number : '';
            $user->attribute->latest_education = $request->latest_education != '' ? $request->latest_education : '';
            $user->attribute->job_experience = $request->job_experience != '' ? $request->job_experience : '';
            $user->attribute->start_date = $request->start_date != '' ? DateTimeExt::change($request->start_date) : null;
            $user->attribute->save();

            // Redirect
            return redirect()->route('admin.employee.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the employee
        $employee = User::find($request->id);

        // Delete the employee
        $employee->delete();

        // Redirect
        return redirect()->route('admin.employee.index')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Export to Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        // Check the access
        has_access(method(__METHOD__), Auth::user()->role_id);

        // Set memory limit
        ini_set("memory_limit", "-1");

        // Get employees
        if(Auth::user()->role->is_global === 1) {
            $company = Company::find($request->query('company'));
            if($company) {
                $employees = User::whereHas('attribute', function (Builder $query) use ($company) {
                    return $query->has('company')->where('company_id','=',$company->id);
                })->where('role_id','=',role('employee'))->get();
            }
            else {
                $employees = User::whereHas('attribute', function (Builder $query) {
                    return $query->has('company');
                })->where('role_id','=',role('employee'))->get();
            }
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $employees = User::whereHas('attribute', function (Builder $query) use ($company) {
                return $query->has('company')->where('company_id','=',$company->id);
            })->where('role_id','=',role('employee'))->get();
        }

        // Set filename
        $filename = $company ? 'Data Karyawan '.$company->name.' ('.date('Y-m-d-H-i-s').')' : 'Data Semua Karyawan ('.date('d-m-Y-H-i-s').')';

        // Return
        return Excel::download(new EmployeeExport($employees), $filename.'.xlsx');
    }
}
