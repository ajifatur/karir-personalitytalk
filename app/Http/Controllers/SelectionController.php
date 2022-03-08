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
    	// Get the HRD
    	if(Auth::user()->role->is_global === 1) {
            $applicant = Pelamar::find($request->applicant_id);
            if($applicant) $hrd = HRD::find($applicant->id_hrd);
        }
    	elseif(Auth::user()->role->is_global === 0) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
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
            $check = Seleksi::where('id_pelamar','=',$request->applicant_id)->where('id_lowongan','=',$request->vacancy_id)->first();

            // If check is exist
            if($check) {
                return redirect()->route('admin.applicant.detail', ['id' => $request->applicant_id])->with(['message' => 'Sudah masuk ke data seleksi.']);
            }

            // Save the selection
            $selection = new Seleksi;
            $selection->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
            $selection->id_pelamar = $request->applicant_id;
            $selection->id_lowongan = $request->vacancy_id;
            $selection->hasil = 99;
            $selection->waktu_wawancara = generate_date_format($request->date, 'y-m-d')." ".$request->time.":00";
            $selection->tempat_wawancara = $request->place;
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
        $selection = Selection::find($request->id);

        // Get the applicant
        $applicant = Pelamar::find($selection->id_pelamar);

        // Get the vacancy
        $vacancy = Lowongan::find($applicant->posisi);

        // Add to employee
        $employee = new Karyawan;
        $employee->id_user = $applicant->id_user;
        $employee->id_hrd = $selection->id_hrd;
        $employee->nama_lengkap = $applicant->nama_lengkap;
        $employee->tanggal_lahir = $applicant->tanggal_lahir;
        $employee->jenis_kelamin = $applicant->jenis_kelamin;
        $employee->email = $applicant->email;
        $employee->nomor_hp = $applicant->nomor_hp;
        $employee->posisi = $vacancy ? $vacancy->posisi : 0;
        $employee->kantor = 0;
        $employee->nik_cis = '';
        $employee->nik = $applicant->nomor_ktp;
        $employee->alamat = $applicant->alamat;
        $employee->pendidikan_terakhir = $applicant->pendidikan_terakhir;
        $employee->awal_bekerja = null;
        $employee->save();

        // Get the user
        $user = User::find($applicant->id_user);
        $user->role_id = role('employee');
        $user->save();

        // Redirect
        return redirect()->route('admin.selection.index')->with(['message' => 'Berhasil mengonversi data.']);
    }
}