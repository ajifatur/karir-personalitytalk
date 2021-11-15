<?php

namespace App\Http\Controllers\Update;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Seleksi;
use App\Models\HRD;
use App\Models\Kantor;
use App\Models\Karyawan;
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
            if($request->query('hrd') != null && $request->query('hasil') != null) {
                $hrd = HRD::find($request->query('hrd'));

                if($hrd && ($request->query('hasil') == 1 || $request->query('hasil') == 0 || $request->query('hasil') == 99)) {
                    // Get selections
                    $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$request->query('hrd'))->where('seleksi.hasil','=',$request->query('hasil'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
                elseif($hrd && ($request->query('hasil') != 1 && $request->query('hasil') != 0 && $request->query('hasil') != 99)) {
                    // Get selections
                    $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$request->query('hrd'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
                elseif(!$hrd && ($request->query('hasil') == 1 || $request->query('hasil') == 0 || $request->query('hasil') == 99)) {
                    // Get selections
                    $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.hasil','=',$request->query('hasil'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
                else {
                    // Get selections
                    $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
            }
            else{
                // Get selections
                $selections = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
            }
            
            // Get offices
            $offices = Kantor::all();
        }
        elseif(Auth::user()->role == role('hrd')) {
			// Get the HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
			
            if($request->query('hasil') != null && ($request->query('hasil') == 1 || $request->query('hasil') == 0 || $request->query('hasil') == 99)){
                // Get selections
                $seleksi = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$hrd->id_hrd)->where('seleksi.hasil','=',$request->query('hasil'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
            }
            else{
    			// Get selections
                $seleksi = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$hrd->id_hrd)->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
            }
            
            // Get offices
            $offices = Kantor::where('id_hrd','=',$hrd->id_hrd)->get();
        }

        // Set selections
        if(count($selections)>0){
            foreach($selections as $key=>$selection){
                $employee = Karyawan::where('id_user','=',$selection->id_user)->first();
                $selections[$key]->isEmployee = !$employee ? false : true;
            }
        }

    	// View
        return view('admin/selection/index', [
            'selections' => $selections,
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

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Generate the permalink
            $permalink = generate_permalink($request->name);
            $i = 1;
            while(count_existing_data('tes', 'path', $permalink, 'id_tes', null) > 0){
                $permalink = rename_permalink(generate_permalink($request->name), $i);
                $i++;
            }

            // Save the test
            $test = new Tes;
            $test->nama_tes = $request->name;
            $test->path = $permalink;
            $test->waktu_tes = null;
            $test->save();

            // Redirect
            return redirect()->route('admin.test.index')->with(['message' => 'Berhasil menambah data.']);
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


        if(Auth::user()->role == role('admin')) {
            // Get the test
            $test = Tes::findOrFail($id);

            // View
        	return view('admin/test/edit', [
                'test' => $test
            ]);
        }
        else abort(403);
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
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Generate the permalink
            $permalink = generate_permalink($request->name);
            $i = 1;
            while(count_existing_data('tes', 'path', $permalink, 'id_tes', $request->id) > 0){
                $permalink = rename_permalink(generate_permalink($request->name), $i);
                $i++;
            }

            // Update the test
            $test = Tes::find($request->id);
            $test->nama_tes = $request->name;
            $test->path = $permalink;
            $test->save();

            // Redirect
            return redirect()->route('admin.test.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the test
        $test = Tes::find($request->id);

        // Delete the test
        $test->delete();

        // Redirect
        return redirect()->route('admin.test.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}