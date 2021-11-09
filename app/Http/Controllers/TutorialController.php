<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\PaketSoal;
use App\Models\Tes;
use App\Models\Tutorial;

class TutorialController extends Controller
{
    /**
     * Menampilkan data tutorial
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

        // Jika tidak ada data paket soal
        if(!$paket){
            abort(404);
        }

    	// Get data
    	$tutorial = Tutorial::where('id_paket','=',$id_paket)->where('id_tes','=',$id_tes)->first();

    	// View
        if(Auth::user()->role == 1){
        	return view('tutorial/admin/tutorial', [
                'tutorial' => $tutorial,
                'id_tes' => $id_tes,
                'id_paket' => $id_paket,
            ]);
        }
        elseif(Auth::user()->role == 2){
            return view('tutorial/hrd/tutorial', [
                'tutorial' => $tutorial,
                'id_tes' => $id_tes,
                'id_paket' => $id_paket,
            ]);
        }
    }

    /**
     * Menyimpan tutorial ke database...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, $id)
    {
        // Pesan Error
        $messages = [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute wajib dengan nomor atau angka.',
            'unique' => ':attribute sudah ada.',
            'min' => ':attribute harus diisi minimal :min karakter.',
            'max' => ':attribute harus diisi maksimal :max karakter.',
        ];

        // Validasi
        $validator = Validator::make($request->all(), [
            'tutorial' => 'required',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menyimpan data
            $tutorial = Tutorial::where('id_paket','=',$request->id_paket)->where('id_tes','=',$request->id_tes)->first();

            if(!$tutorial){
	            $tutorial = new Tutorial;
	        }
	        
            $tutorial->id_paket = $request->id_paket;
            $tutorial->id_tes = $request->id_tes;
            $tutorial->tutorial = htmlentities($request->tutorial);
            $tutorial->save();
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('/admin/tes/tipe/'.$request->id_tes.'/paket/tutorial/'.$request->id_paket)->with(['message' => 'Berhasil menambah/memperbarui data.']);
        }
        elseif(Auth::user()->role == 2){
            return redirect('/hrd/tes/tipe/'.$request->id_tes.'/paket/tutorial/'.$request->id_paket)->with(['message' => 'Berhasil menambah/memperbarui data.']);
        }
    }

    /**
     * Menghapus tutorial...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        // Menghapus data
        $tutorial = Tutorial::find($request->id);
        if($tutorial->delete()){
            echo "Berhasil menghapus data!";
        }
    }
}
