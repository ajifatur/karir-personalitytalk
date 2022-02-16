<?php

namespace App\Http\Controllers\Update;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Seleksi;
use App\Models\HRD;
use App\Models\Kantor;
use App\Models\Karyawan;
use App\Models\Lowongan;
use App\Models\Pelamar;
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
        // has_access(method(__METHOD__), Auth::user()->role_id);

        if(Auth::user()->role == role('admin')) {
            if($request->query('hrd') != null && $request->query('result') != null) {
                $hrd = HRD::find($request->query('hrd'));

                if($hrd && ($request->query('result') == 1 || $request->query('result') == 2 || $request->query('result') == 0 || $request->query('result') == 99)) {
                    // Get selections
                    $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$request->query('hrd'))->where('seleksi.hasil','=',$request->query('result'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
                elseif($hrd && ($request->query('result') != 1 && $request->query('result') != 2 && $request->query('result') != 0 && $request->query('result') != 99)) {
                    // Get selections
                    $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$request->query('hrd'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
                elseif(!$hrd && ($request->query('result') == 1 || $request->query('result') == 2 || $request->query('result') == 0 || $request->query('result') == 99)) {
                    // Get selections
                    $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.hasil','=',$request->query('result'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
                else {
                    // Get selections
                    $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
            }
            else {
                // Get selections
                $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
            }
            
            // Get offices
            $offices = Kantor::all();
        }
        elseif(Auth::user()->role == role('hrd')) {
			// Get the HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
			
            if($request->query('result') != null && ($request->query('result') == 1 || $request->query('result') == 2 || $request->query('result') == 0 || $request->query('result') == 99)) {
                // Get selections
                $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$hrd->id_hrd)->where('seleksi.hasil','=',$request->query('result'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
            }
            else {
    			// Get selections
                $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$hrd->id_hrd)->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
            }
            
            // Get offices
            $offices = Kantor::where('id_hrd','=',$hrd->id_hrd)->get();
        }

        // Set selections
        if(count($selections)>0) {
            foreach($selections as $key=>$selection) {
                $employee = Karyawan::where('id_user','=',$selection->id_user)->first();
                $selections[$key]->isEmployee = !$employee ? false : true;
            }
        }

        // Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

    	// View
        return view('admin/selection/index', [
            'selections' => $selections,
            'hrds' => $hrds,
            'offices' => $offices,
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
    	// Get the HRD
    	if(Auth::user()->role == role('hrd')) {
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
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);
        
        if($request->ajax()) {
            // Get the selection
            $selection = Seleksi::find($request->id);
            $selection->tanggal_wawancara = date('d/m/Y', strtotime($selection->waktu_wawancara));

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
            $selection = Seleksi::find($request->id);
            $selection->waktu_wawancara = $request->result == 99 ? generate_date_format($request->date, 'y-m-d')." ".$request->time.":00" : $selection->waktu_wawancara;
            $selection->tempat_wawancara = $request->result == 99 ? $request->place : $selection->tempat_wawancara;
            $selection->hasil = $request->result;
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
        // has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the selection
        $selection = Seleksi::find($request->id);

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
        // has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the selection
        $selection = Seleksi::find($request->id);

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
        $user->role = role_karyawan();
        $user->save();

        // Redirect
        return redirect()->route('admin.selection.index')->with(['message' => 'Berhasil mengonversi data.']);
    }
}