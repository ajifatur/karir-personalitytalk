<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Keterangan;
use App\Models\PaketSoal;
use App\Models\Tes;

class KeteranganController extends Controller
{
    /**
     * Menampilkan data keterangan
     * 
     * int $id_tes
     * int $id_paket
     * @return \Illuminate\Http\Response
     */
    public function index($id_tes, $id_paket)
    {
        // Get data tes
        $tes = Tes::find($id_tes);

        // Jika tidak ada data tes
        if(!$tes){
            abort(404);
        }

    	// Get data paket soal
    	$paket = PaketSoal::find($id_paket);

        // Jika tidak ada paket soal
        if(!$paket){
            abort(404);
        }

    	// Get data keterangan
    	$keterangan = Keterangan::where('id_tes','=',$id_tes)->where('id_paket','=',$id_paket)->first();
    	if(!$keterangan){
    	    $keterangan = false;
    	}
    	else{
            $keterangan->keterangan = json_decode($keterangan->keterangan, true);
    	}

    	// View
        if(Auth::user()->role == 1){
        	return view('keterangan/admin/'.$tes->path, [
        		'keterangan' => $keterangan,
                'id_paket' => $id_paket,
        		'id_tes' => $id_tes
        	]);
        }
        elseif(Auth::user()->role == 2){
            return view('keterangan/hrd/'.$tes->path, [
                'keterangan' => $keterangan,
                'id_paket' => $id_paket,
                'id_tes' => $id_tes
            ]);
        }
    }

    /**
     * Menyimpan keterangan ke database...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, $id)
    {
        if($id == 1){
            return $this->save_disc($request, $id);
        }
        elseif($id == 6){
            return $this->save_msdt($request, $id);
        }
    }

    /**
     * Menyimpan keterangan DISC ke database...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save_disc(Request $request, $id)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'd' => 'required',
            'i' => 'required',
            's' => 'required',
            'c' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Mengmabil data
            $keterangan = Keterangan::where('id_tes','=',$request->id_tes)->where('id_paket','=',$request->id_paket)->first();

            if(!$keterangan){
	            $keterangan = new Keterangan;
	            
    	        // Array keterangan
    	        $array = array(
    	            array(
    	                "disc" => "D",
    	                "keterangan" => htmlentities($request->d)
    	            ),
    	            array(
    	                "disc" => "I",
    	                "keterangan" => htmlentities($request->i)
    	            ),
    	            array(
    	                "disc" => "S",
    	                "keterangan" => htmlentities($request->s)
    	            ),
    	            array(
    	                "disc" => "C",
    	                "keterangan" => htmlentities($request->c)
    	            ),
    	        );
	        }
	        else{
    	        // Array keterangan
    	        $array = json_decode($keterangan->keterangan, true);
    	        $array[searchIndex($array, "disc", "D")]["keterangan"] = htmlentities($request->d);
                $array[searchIndex($array, "disc", "I")]["keterangan"] = htmlentities($request->i);
                $array[searchIndex($array, "disc", "S")]["keterangan"] = htmlentities($request->s);
                $array[searchIndex($array, "disc", "C")]["keterangan"] = htmlentities($request->c);
	        }
	        
	        // Menyimpan data
	        $keterangan->id_tes = $request->id_tes;
            $keterangan->id_paket = $request->id_paket;
            $keterangan->keterangan = json_encode($array);
            $keterangan->save();
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('/admin/tes/tipe/'.$request->id_tes.'/paket/keterangan/'.$request->id_paket)->with(['message' => 'Berhasil menambah/memperbarui data.']);
        }
        elseif(Auth::user()->role == 2){
            return redirect('/hrd/tes/tipe/'.$request->id_tes.'/paket/keterangan/'.$request->id_paket)->with(['message' => 'Berhasil menambah/memperbarui data.']);
        }
    }

    /**
     * Menyimpan keterangan MSDT ke database...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save_msdt(Request $request, $id)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'deserter' => 'required',
            'bereaucrat' => 'required',
            'missionary' => 'required',
            'developer' => 'required',
            'autocrat' => 'required',
            'benevolent_autocrat' => 'required',
            'compromiser' => 'required',
            'executive' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Mengmabil data
            $keterangan = Keterangan::where('id_tes','=',$request->id_tes)->where('id_paket','=',$request->id_paket)->first();

            if(!$keterangan){
	            $keterangan = new Keterangan;
	            
    	        // Array keterangan
    	        $array = array(
    	            array(
    	                "tipe" => "deserter",
    	                "keterangan" => htmlentities($request->deserter)
    	            ),
    	            array(
    	                "tipe" => "bereaucrat",
    	                "keterangan" => htmlentities($request->bereaucrat)
    	            ),
    	            array(
    	                "tipe" => "missionary",
    	                "keterangan" => htmlentities($request->missionary)
    	            ),
    	            array(
    	                "tipe" => "developer",
    	                "keterangan" => htmlentities($request->developer)
    	            ),
    	            array(
    	                "tipe" => "autocrat",
    	                "keterangan" => htmlentities($request->autocrat)
    	            ),
    	            array(
    	                "tipe" => "benevolent_autocrat",
    	                "keterangan" => htmlentities($request->benevolent_autocrat)
    	            ),
    	            array(
    	                "tipe" => "compromiser",
    	                "keterangan" => htmlentities($request->compromiser)
    	            ),
    	            array(
    	                "tipe" => "executive",
    	                "keterangan" => htmlentities($request->executive)
    	            ),
    	        );
	        }
	        else{
    	        // Array keterangan
    	        $array = json_decode($keterangan->keterangan, true);
    	        $array[searchIndex($array, "tipe", "deserter")]["keterangan"] = htmlentities($request->deserter);
                $array[searchIndex($array, "tipe", "bereaucrat")]["keterangan"] = htmlentities($request->bereaucrat);
                $array[searchIndex($array, "tipe", "missionary")]["keterangan"] = htmlentities($request->missionary);
                $array[searchIndex($array, "tipe", "developer")]["keterangan"] = htmlentities($request->developer);
    	        $array[searchIndex($array, "tipe", "autocrat")]["keterangan"] = htmlentities($request->autocrat);
    	        $array[searchIndex($array, "tipe", "benevolent_autocrat")]["keterangan"] = htmlentities($request->benevolent_autocrat);
    	        $array[searchIndex($array, "tipe", "compromiser")]["keterangan"] = htmlentities($request->compromiser);
    	        $array[searchIndex($array, "tipe", "executive")]["keterangan"] = htmlentities($request->executive);
	        }
	        
	        // Menyimpan data
	        $keterangan->id_tes = $request->id_tes;
            $keterangan->id_paket = $request->id_paket;
            $keterangan->keterangan = json_encode($array);
            $keterangan->save();
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('/admin/tes/tipe/'.$request->id_tes.'/paket/keterangan/'.$request->id_paket)->with(['message' => 'Berhasil menambah/memperbarui data.']);
        }
        elseif(Auth::user()->role == 2){
            return redirect('/hrd/tes/tipe/'.$request->id_tes.'/paket/keterangan/'.$request->id_paket)->with(['message' => 'Berhasil menambah/memperbarui data.']);
        }
    }


    /**
     * Menghapus keterangan...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        // Menghapus data
        $keterangan = Keterangan::find($request->id);
        if($keterangan->delete()){
            echo "Berhasil menghapus data!";
        }
    }
}
