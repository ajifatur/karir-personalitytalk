<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\HRD;
use App\Models\Posisi;
use App\Models\Tes;

class PosisiController extends Controller
{
    /**
     * Menampilkan data jabatan
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	// Get data jabatan
        if(Auth::user()->role == 1){
			if($request->query('hrd') != null){
            	$hrd = HRD::find($request->query('hrd'));
    	    	$posisi = $hrd ? Posisi::join('hrd','posisi.id_hrd','=','hrd.id_hrd')->where('hrd.id_hrd','=',$request->query('hrd'))->get() : Posisi::join('hrd','posisi.id_hrd','=','hrd.id_hrd')->get();
			}
			else{
    	    	$posisi = Posisi::join('hrd','posisi.id_hrd','=','hrd.id_hrd')->get();
			}
        }
        elseif(Auth::user()->role == 2){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $posisi = Posisi::where('id_hrd','=',$hrd->id_hrd)->get();
        }
        
        // Setting data jabatan
    	foreach($posisi as $data){
            // Tes
    		if($data->tes != ''){
	    		$tes_id = explode(',', $data->tes);
	    		$array = array();
	    		foreach($tes_id as $id){
	    			$tes = Tes::find($id);
	    			array_push($array, $tes->nama_tes);
	    		}
	    		$data->tes = implode(', ', $array);
	    	}
	    	else{
	    		$data->tes = '-';
	    	}

            // Keahlian
            if($data->keahlian != ''){
                $keahlian = explode(',', $data->keahlian);
                $data->keahlian = implode(', ', $keahlian);
            }
            else{
                $data->keahlian = '-';
            }
    	}

    	// View
        return view('posisi/index', [
            'posisi' => $posisi,
        ]);
    }

    /**
     * Menampilkan form input jabatan
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	// Get data HRD
    	$hrd = HRD::all();
    	
    	// Get data tes
        if(Auth::user()->role == 1){
    	    $tes = Tes::all();
        }
        elseif(Auth::user()->role == 2){
            $user = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $ids = explode(',', $user->akses_tes);
            $tes = Tes::whereIn('id_tes',$ids)->get();
        }

        // View
        return view('posisi/create', [
            'hrd' => $hrd,
            'tes' => $tes,
        ]);
    }

    /**
     * Menyimpan data jabatan
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
        
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_jabatan' => 'required',
            'hrd' => Auth::user()->role == role_admin() ? 'required' : '',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $posisi = new Posisi;
            $posisi->id_hrd = isset($hrd) ? $hrd->id_hrd : $request->hrd;
            $posisi->nama_posisi = $request->nama_jabatan;
            $posisi->tes = !empty($request->get('tes')) ? implode(',', array_filter($request->get('tes'))) : '';
            $posisi->keahlian = !empty($request->get('keahlian')) ? implode(',', array_filter($request->get('keahlian'))) : '';
            $posisi->save();
        }

        // Redirect
        return redirect('admin/posisi')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Menampilkan form edit jabatan
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	// Get data HRD dan jabatan
    	if(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $posisi = Posisi::where('id_posisi','=',$id)->where('id_hrd','=',$hrd->id_hrd)->first();
        }
        else{
            $posisi = Posisi::find($id);
        }

        // Jika tidak ada data jabatan
        if(!$posisi){
            abort(404);
        }
        else{
        	$posisi->tes = $posisi->tes != '' ? explode(',', $posisi->tes) : array();
            $posisi->keahlian = $posisi->keahlian != '' ? explode(',', $posisi->keahlian) : array();
        }

    	// Get data tes
    	$tes = get_perusahaan_tes($posisi->id_hrd);

        // View
        return view('posisi/edit', [
            'posisi' => $posisi,
            'tes' => $tes
        ]);
    }

    /**
     * Mengupdate data jabatan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_jabatan' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Mengupdate data
            $posisi = Posisi::find($request->id);
            $posisi->nama_posisi = $request->nama_jabatan;
            $posisi->tes = !empty($request->get('tes')) ? implode(',', array_filter($request->get('tes'))) : '';
            $posisi->keahlian = !empty($request->get('keahlian')) ? implode(',', array_filter($request->get('keahlian'))) : '';
            $posisi->save();
        }

        // Redirect
        return redirect('admin/posisi')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Menghapus data jabatan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $posisi = Posisi::find($request->id);
        $posisi->delete();

        // Redirect
        return redirect('admin/posisi')->with(['message' => 'Berhasil menghapus data.']);
    }
}
