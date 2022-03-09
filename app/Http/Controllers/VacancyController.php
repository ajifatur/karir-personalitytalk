<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Vacancy;
use App\Models\Company;
use App\Models\User;
use App\Models\Position;
use App\Models\Selection;

class VacancyController extends \App\Http\Controllers\Controller
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
            // Get vacancies
            $vacancies = Vacancy::has('company')->has('position')->where('company_id','=',$request->query('company'))->where('status','=',1)->orderBy('name','asc')->get();
            foreach($vacancies as $vacancy) {
                $vacancy->name = $vacancy->name.', sebagai '.$vacancy->position->name;
            }

            // Return
            return response()->json($vacancies);
        }

        // Get vacancies
        if(Auth::user()->role->is_global === 1) {
            $company = Company::find($request->query('company'));
            $vacancies = $company ? $company->vacancies()->orderBy('status','desc')->orderBy('created_at','desc')->get() : Vacancy::has('company')->has('position')->orderBy('status','desc')->orderBy('created_at','desc')->get();
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $vacancies = $company ? $company->vacancies()->orderBy('status','desc')->orderBy('created_at','desc')->get() : [];
        }

        // Set
        foreach($vacancies as $key=>$vacancy) {
            $vacancy_id = $vacancy->id;
            $vacancies[$key]->applicants = User::whereHas('attribute', function (Builder $query) use ($vacancy_id) {
                return $query->where('vacancy_id','=',$vacancy_id);
            })->count();
        }

        // Get companies
        $companies = Company::orderBy('name','asc')->get();

        // View
        return view('admin/vacancy/index', [
            'vacancies' => $vacancies,
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

        // Get positions
        if(Auth::user()->role->is_global === 1) {
            $positions = Position::has('company')->orderBy('company_id','asc')->orderBy('name','asc')->get();
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $positions = $company ? $company->positions()->orderBy('name','asc')->get() : [];
        }

        // View
        return view('admin/vacancy/create', [
            'positions' => $positions
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
    	// Get data company
    	if(Auth::user()->role_id == role('hrd')) {
            $company = Company::find(Auth::user()->attribute->company_id);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'position' => 'required',
            'status' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Get the file
            $file = $request->file('file');
            $file_name = $file != null ? date('Y-m-d-H-i-s').'.'.$file->getClientOriginalExtension() : '';
    
            // Move file
            if($file != null)
                $file->move('assets/images/lowongan', $file_name);

            // Get the position
            $position = Position::find($request->position);

            // Save the vacancy
            $vacancy = new Vacancy;
            $vacancy->company_id = $position ? $position->company_id : 0;
            $vacancy->position_id = $request->position;
            $vacancy->name = $request->name;
            $vacancy->description = quill($request->description, 'assets/images/lowongan-content/');
            $vacancy->image = $file_name;
            $vacancy->code = '';
            $vacancy->status = $request->status;
            $vacancy->save();
            $vacancy->code = md5($vacancy->id);
            $vacancy->save();

            // Redirect
            return redirect()->route('admin.vacancy.index')->with(['message' => 'Berhasil menambah data.']);
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

        // Get the vacancy and positions
    	if(Auth::user()->role->is_global === 1) {
            $vacancy = Vacancy::has('company')->has('position')->findOrFail($id);
            $positions = Position::has('company')->where('company_id','=',$vacancy->company_id)->orderBy('name','asc')->get();
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $vacancy = Vacancy::has('position')->where('company_id','=',$company->id)->findOrFail($id);
            $positions = $company ? $company->positions()->orderBy('name','asc')->get() : [];
        }

        // View
        return view('admin/vacancy/edit', [
            'vacancy' => $vacancy,
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
            'position' => 'required',
            'status' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Get the file
            $file = $request->file('file');
            $file_name = $file != null ? date('Y-m-d-H-i-s').'.'.$file->getClientOriginalExtension() : '';
    
            // Move file
            if($file != null)
                $file->move('assets/images/lowongan', $file_name);

            // Update the vacancy
            $vacancy = Vacancy::find($request->id);
            $vacancy->position_id = $request->position;
            $vacancy->name = $request->name;
            $vacancy->description = quill($request->description, 'assets/images/lowongan-content/');
            $vacancy->image = $file_name != '' ? $file_name : $vacancy->image;
            $vacancy->status = $request->status;
            $vacancy->save();

            // Redirect
            return redirect()->route('admin.vacancy.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the vacancy
        $vacancy = Vacancy::find($request->id);

        // Delete the vacancy
        $vacancy->delete();

        // Redirect
        return redirect()->route('admin.vacancy.index')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Display applicants.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function applicant($id)
    {
        // Get the vacancy and positions
    	if(Auth::user()->role->is_global === 1) {
            $vacancy = Vacancy::has('company')->has('position')->findOrFail($id);
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $vacancy = Vacancy::has('position')->where('company_id','=',$company->id)->findOrFail($id);
        }

        // Get applicants
        $applicants = User::whereHas('attribute', function (Builder $query) use ($id) {
            return $query->where('vacancy_id','=',$id);
        })->orderBy('created_at','desc')->get();
        foreach($applicants as $key=>$applicant) {
            // Get the selection
            $selection = Selection::where('user_id','=',$applicant->id)->where('vacancy_id','=',$vacancy->id)->first();

            if($selection) {
                if($selection->status == 0) {
                    $applicants[$key]->badge_color = 'danger';
                    $applicants[$key]->status = 'Tidak Direkomendasikan';
                }
                elseif($selection->status == 1) {
                    $applicants[$key]->badge_color = 'success';
                    $applicants[$key]->status = 'Direkomendasikan';
                }
                elseif($selection->status == 2) {
                    $applicants[$key]->badge_color = 'info';
                    $applicants[$key]->status = 'Dipertimbangkan';
                }
                elseif($selection->status == 99) {
                    $applicants[$key]->badge_color = 'warning';
                    $applicants[$key]->status = 'Belum Dites';
                }
            }
            else {
                $applicants[$key]->badge_color = 'info';
                $applicants[$key]->status = 'Belum Diseleksi';
            }

            $applicants[$key]->isEmployee = $applicant->role_id == role('employee') ? true : false;
        }

        // View
        return view('admin/vacancy/applicant', [
            'vacancy' => $vacancy,
            'applicants' => $applicants,
        ]);
    }

    /**
     * Update status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        // Update status
        $vacancy = Vacancy::find($request->id);
        $vacancy->status = $request->status;
        if($vacancy->save()){
            echo "Berhasil mengupdate status!";
        }
    }

    /**
     * Visit.
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function visit($url)
    {
        // Get the vacancy
        $vacancy = Vacancy::where('code','=',$url)->where('status','=',1)->firstOrFail();

        // Redirect
        return redirect('/lowongan/'.$url.'/daftar/step-1')->with(['posisi' => $vacancy->position_id]);
    }
}