<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Ajifatur\Helpers\DateTimeExt;
use App\Exports\ApplicantExport;
use App\Models\Company;
use App\Models\User;
use App\Models\UserAttribute;
use App\Models\UserGuardian;
use App\Models\UserSocmed;
use App\Models\Selection;
use App\Models\Vacancy;

class ApplicantController extends \App\Http\Controllers\Controller
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
            // Get applicants
            if(Auth::user()->role->is_global === 1) {
                $company = Company::find($request->query('company'));
                if($company) {
                    $applicants = User::whereHas('attribute', function (Builder $query) use ($company) {
                        return $query->has('company')->where('company_id','=',$company->id);
                    })->where('role_id','=',role('applicant'))->get();
                }
                else {
                    $applicants = User::whereHas('attribute', function (Builder $query) {
                        return $query->has('company');
                    })->where('role_id','=',role('applicant'))->get();
                }
            }
            elseif(Auth::user()->role->is_global === 0) {
                $company = Company::find(Auth::user()->attribute->company_id);
                $applicants = User::whereHas('attribute', function (Builder $query) use ($company) {
                    return $query->has('company')->where('company_id','=',$company->id);
                })->where('role_id','=',role('applicant'))->get();
            }

            // Set
            if(count($applicants) > 0) {
                foreach($applicants as $key=>$applicant) {
                    $applicants[$key]->phone_number = $applicant->attribute->phone_number;
                    $applicants[$key]->company_name = $applicant->attribute->company->name;
                    $applicants[$key]->position_name = $applicant->attribute->position ? $applicant->attribute->position->name : '-';
                }
            }

            // Return
            return DataTables::of($applicants)
                ->addColumn('checkbox', '<input type="checkbox" class="form-check-input checkbox-one">')
                ->editColumn('name', '
                    <span class="d-none">{{ $name }}</span>
                    <a href="{{ route(\'admin.applicant.detail\', [\'id\' => $id]) }}">{{ ucwords($name) }}</a>
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
                        <a href="{{ route(\'admin.applicant.detail\', [\'id\' => $id]) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Lihat Detail"><i class="bi-eye"></i></a>
                        <a href="{{ route(\'admin.applicant.edit\', [\'id\' => $id]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
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
        return view('admin/applicant/index', [
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

        // Get vacancies
        if(Auth::user()->role->is_global === 1) {
            $vacancies = [];
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $vacancies = $company ? $company->vacancies()->has('position')->where('status','=',1)->orderBy('name','asc')->get() : [];
        }

        // View
        return view('admin/applicant/create', [
            'companies' => $companies,
            'vacancies' => $vacancies
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
            'vacancy' => 'required',
            'name' => 'required|min:3|max:255',
            'birthdate' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'address' => 'required',
            'relationship' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Get the vacancy and company
            $vacancy = Vacancy::has('company')->find($request->vacancy);
            
            // Generate username
            $data_user = User::whereHas('attribute', function (Builder $query) use ($vacancy) {
                return $query->has('company')->where('company_id','=',$vacancy->company_id);
            })->where('username','like',$vacancy->company->code.'%')->latest('username')->first();
            if(!$data_user)
                $username = generate_username(null, $vacancy->company->code);
            else
                $username = generate_username($data_user->username, $vacancy->company->code);

            var_dump($data_user);
            return;

            // Save the user
            $user = new User;
            $user->role_id = role('applicant');
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $username;
            $user->password = bcrypt($username);
            $user->access_token = null;
            $user->avatar = '';
            $user->status = 1;
            $user->last_visit = null;
            $user->save();

            // Save the user attributes
            $user_attribute = new UserAttribute;
            $user_attribute->user_id = $user->id;
            $user_attribute->company_id = $vacancy->company->id;
            $user_attribute->office_id = 0;
            $user_attribute->position_id = $vacancy->position_id;
            $user_attribute->vacancy_id = $vacancy->id;
            $user_attribute->birthdate = DateTimeExt::change($request->birthdate);
            $user_attribute->birthplace = $request->birthplace != '' ? $request->birthplace : '';
            $user_attribute->gender = $request->gender;
            $user_attribute->country_code = 'ID';
            $user_attribute->dial_code = '+62';
            $user_attribute->phone_number = $request->phone_number;
            $user_attribute->address = $request->address;
            $user_attribute->identity_number = $request->identity_number != '' ? $request->identity_number : '';
            $user_attribute->religion = $request->religion;
            $user_attribute->relationship = $request->relationship;
            $user_attribute->latest_education = $request->latest_education != '' ? $request->latest_education : '';
            $user_attribute->job_experience = $request->job_experience != '' ? $request->job_experience : '';
            $user_attribute->start_date = null;
            $user_attribute->end_date = null;
            $user_attribute->save();

            // Redirect
            return redirect()->route('admin.applicant.index')->with(['message' => 'Berhasil menambah data.']);
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

        // Get the applicant
        if(Auth::user()->role->is_global === 1) {
            $applicant = User::whereHas('attribute', function (Builder $query) {
                return $query->has('company')->has('vacancy');
            })->where('role_id','=',role('applicant'))->findOrFail($id);
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $applicant = User::whereHas('attribute', function (Builder $query) use ($company) {
                return $query->has('company')->has('vacancy')->where('company_id','=',$company->id);
            })->where('role_id','=',role('applicant'))->findOrFail($id);
        }

        // Get attachments
        $photo = $applicant->attachments()->where('attachment_id','=',1)->first();
        $applicant->photo = $photo ? $photo->file : '';
        $certificate = $applicant->attachments()->where('attachment_id','=',2)->first();
        $applicant->certificate = $certificate ? $certificate->file : '';

        // Get the selection
        $selection = Selection::has('user')->has('company')->has('vacancy')->where('user_id','=',$applicant->id)->where('vacancy_id','=',$applicant->attribute->vacancy_id)->first();

        // View
        return view('admin/applicant/detail', [
            'applicant' => $applicant,
            'selection' => $selection
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

        // Get the applicant
        if(Auth::user()->role->is_global === 1) {
            $applicant = User::whereHas('attribute', function (Builder $query) {
                return $query->has('company');
            })->where('role_id','=',role('applicant'))->findOrFail($id);
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $applicant = User::whereHas('attribute', function (Builder $query) use ($company) {
                return $query->has('company')->where('company_id','=',$company->id);
            })->where('role_id','=',role('applicant'))->findOrFail($id);
        }

        // View
        return view('admin/applicant/edit', [
            'applicant' => $applicant
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
            'name' => 'required|min:3|max:255',
            'birthplace' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'address' => 'required',
            'relationship' => 'required',
            'socmed' => 'required',
            'guardian_name' => 'required',
            'guardian_address' => 'required',
            'guardian_phone_number' => 'required|numeric',
            'guardian_occupation' => 'required',
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
            $user->save();

            // Update the user attribute
            $user->attribute->birthdate = DateTimeExt::change($request->birthdate);
            $user->attribute->gender = $request->gender;
            $user->attribute->phone_number = $request->phone_number;
            $user->attribute->address = $request->address;
            $user->attribute->religion = $request->religion;
            $user->attribute->relationship = $request->relationship;
            $user->attribute->identity_number = $request->identity_number != '' ? $request->identity_number : '';
            $user->attribute->latest_education = $request->latest_education != '' ? $request->latest_education : '';
            $user->attribute->job_experience = $request->job_experience != '' ? $request->job_experience : '';
            $user->attribute->save();

            // Update or create the user socmed
            $user_socmed = UserSocmed::where('user_id','=',$user->id)->first();
            if(!$user_socmed) $user_socmed = new UserSocmed;
            $user_socmed->user_id = $user->id;
            $user_socmed->platform = $request->platform;
            $user_socmed->account = $request->socmed;
            $user_socmed->save();

            // Update or create the user guardian
            $user_guardian = UserGuardian::where('user_id','=',$user->id)->first();
            if(!$user_guardian) $user_guardian = new UserGuardian;
            $user_guardian->user_id = $user->id;
            $user_guardian->name = $request->guardian_name;
            $user_guardian->address = $request->guardian_address;
            $user_guardian->country_code = 'ID';
            $user_guardian->dial_code = '+62';
            $user_guardian->phone_number = $request->guardian_phone_number;
            $user_guardian->occupation = $request->guardian_occupation;
            $user_guardian->save();

            // Redirect
            return redirect()->route('admin.applicant.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the applicant
        $applicant = User::find($request->id);

        // Delete the applicant
        $applicant->delete();
        
        // Get the selection
        $selection = Selection::where('user_id','=',$request->id)->first();

        // Delete the selection
        if($selection) $selection->delete();

        // Redirect
        return redirect()->route('admin.applicant.index')->with(['message' => 'Berhasil menghapus data.']);
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

        // Get applicants
        if(Auth::user()->role->is_global === 1) {
            $company = Company::find($request->query('company'));
            if($company) {
                $applicants = User::whereHas('attribute', function (Builder $query) use ($company) {
                    return $query->has('company')->where('company_id','=',$company->id);
                })->where('role_id','=',role('applicant'))->get();
            }
            else {
                $applicants = User::whereHas('attribute', function (Builder $query) {
                    return $query->has('company');
                })->where('role_id','=',role('applicant'))->get();
            }
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $applicants = User::whereHas('attribute', function (Builder $query) use ($company) {
                return $query->has('company')->where('company_id','=',$company->id);
            })->where('role_id','=',role('applicant'))->get();
        }

        // Set filename
        $filename = $company ? 'Data Pelamar '.$company->name.' ('.date('Y-m-d-H-i-s').')' : 'Data Semua Pelamar ('.date('d-m-Y-H-i-s').')';

        // Return
        return Excel::download(new ApplicantExport($applicants), $filename.'.xlsx');

        if(Auth::user()->role->is_global === 1) {
            // Get the HRD
            $hrd = HRD::find($request->query('hrd'));

            // Get applicants
            $applicants = $hrd ? Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_hrd','=',$hrd->id_hrd)->get() : Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->get();

            // File name
            $filename = $hrd ? 'Data Pelamar '.$hrd->perusahaan.' ('.date('Y-m-d-H-i-s').')' : 'Data Semua Pelamar ('.date('d-m-Y-H-i-s').')';

            return Excel::download(new PelamarExport($applicants), $filename.'.xlsx');
        }
        elseif(Auth::user()->role->is_global === 0) {
            // Get the HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id)->first();

            // Get applicants
            $applicants = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_hrd','=',$hrd->id_hrd)->get();

            // File name
            $filename = 'Data Pelamar '.$hrd->perusahaan.' ('.date('Y-m-d-H-i-s').')';

            return Excel::download(new PelamarExport($applicants), $filename.'.xlsx');
        }
    }
}
