<?php

namespace App\Http\Controllers\Update;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lowongan;
use App\Models\HRD;
use App\Models\Pelamar;
use App\Models\Posisi;
use App\Models\Seleksi;

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
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Get offices
        if(Auth::user()->role == role('admin')) {
            if($request->query('hrd') != null) {
            	$hrd = HRD::find($request->query('hrd'));
    	    	$vacancies = $hrd ? Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('hrd.id_hrd','=',$request->query('hrd'))->orderBy('status','desc')->orderBy('created_at','desc')->get() : Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('status','desc')->orderBy('created_at','desc')->get();
            }
            else{
				$vacancies = Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('status','desc')->orderBy('created_at','desc')->get();
            }
        }
        elseif(Auth::user()->role == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
    	    $vacancies = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('lowongan.id_hrd','=',$hrd->id_hrd)->orderBy('status','desc')->orderBy('created_at','desc')->get();
        }

        // Set
        foreach($vacancies as $key=>$vacancy){
            $pelamar = Pelamar::where('posisi','=',$vacancy->id_lowongan)->count();
            $vacancies[$key]->pelamar = $pelamar;
        }

        // View
        return view('admin/vacancy/index', [
            'vacancies' => $vacancies
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

        // Get tests
        if(Auth::user()->role == role('admin')) {
            $positions = Posisi::orderBy('nama_posisi','asc')->get();
        }
        elseif(Auth::user()->role == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $positions = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
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
    	// Get data HRD
    	if(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'position' => 'required',
            'status' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Get the position
            $position = Posisi::find($request->position);

            // Save the vacancy
            $vacancy = new Lowongan;
            $vacancy->id_hrd = $position ? $position->id_hrd : 0;
            $vacancy->judul_lowongan = $request->name;
            $vacancy->deskripsi_lowongan = '';
            $vacancy->posisi = $request->position;
            $vacancy->url_lowongan = '';
            $vacancy->status = $request->status;
            $vacancy->created_at = date("Y-m-d H:i:s");
            $vacancy->save();
            $vacancy->url_lowongan = md5($vacancy->id_lowongan);
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
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Get the vacancy
    	if(Auth::user()->role == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $vacancy = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_lowongan','=',$id)->where('lowongan.id_hrd','=',$hrd->id_hrd)->firstOrFail();
        }
        else {
            $vacancy = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->findOrFail($id);
        }

        // Get positions
        if(Auth::user()->role == role_admin()){
            $positions = Posisi::where('id_hrd','=',$vacancy->id_hrd)->orderBy('nama_posisi','asc')->get();
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $positions = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
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
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the vacancy
            $vacancy = Lowongan::find($request->id);
            $vacancy->judul_lowongan = $request->name;
            $vacancy->posisi = $request->position;
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
        // has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the vacancy
        $vacancy = Lowongan::find($request->id);

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
        // Get the vacancy
        if(Auth::user()->role == role_admin()){
            $vacancy = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->findOrFail($id);
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $vacancy = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('lowongan.id_hrd','=',$hrd->id_hrd)->findOrFail($id);
        }

        // Get applicants
        $applicants = Pelamar::join('users','pelamar.id_user','=','users.id_user')->where('posisi','=',$vacancy->id_lowongan)->orderBy('pelamar_at','desc')->get();
        foreach($applicants as $key=>$applicant){
            $selection = Seleksi::where('id_pelamar','=',$applicant->id_pelamar)->where('id_lowongan','=',$id)->first();
            if(!$selection) {
                $applicants[$key]->badge_color = 'info';
                $applicants[$key]->hasil = 'Belum Diseleksi';
            }
            else {
                if($selection->hasil == 0) {
                    $applicants[$key]->badge_color = 'danger';
                    $applicants[$key]->hasil = 'Tidak Lolos';
                }
                elseif($selection->hasil == 1) {
                    $applicants[$key]->badge_color = 'success';
                    $applicants[$key]->hasil = 'Lolos';
                }
                elseif($selection->hasil == 99) {
                    $applicants[$key]->badge_color = 'warning';
                    $applicants[$key]->hasil = 'Belum Dites';
                }
            }
            
            $applicants[$key]->isKaryawan = $applicant->role == role_karyawan() ? true : false;
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
        $vacancy = Lowongan::find($request->id);
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
        $vacancy = Lowongan::where('url_lowongan','=',$url)->where('status','=',1)->firstOrFail();

        // Redirect
        return redirect('/lowongan/'.$url.'/daftar/step-1')->with(['posisi' => $vacancy->posisi]);
    }
}