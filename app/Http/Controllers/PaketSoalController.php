<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\PaketSoal;
use App\Models\Soal;
use App\Models\Tes;

class PaketSoalController extends Controller
{
    /**
     * Menampilkan data soal
     * 
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        // Get data tes
        $tes = Tes::find($id);

        // Jika tidak ada data tes
        if(!$tes){
            abort(404);
        }

    	// Get data paket soal
    	$paket = PaketSoal::where('id_tes','=',$id)->orderBy('status','desc')->get();

        // Get data paket soal aktif
        $paket_aktif = PaketSoal::where('id_tes','=',$id)->where('status','=',1)->get();

    	// View
        if(Auth::user()->role == 1){
        	return view('paket-soal/admin/index', [
        		'paket' => $paket,
                'paket_aktif' => $paket_aktif,
                'tes' => $tes,
        	]);
        }
        elseif(Auth::user()->role == 2){
            return view('paket-soal/hrd/index', [
                'paket' => $paket,
                'paket_aktif' => $paket_aktif,
                'tes' => $tes,
            ]);
        }
    }

    /**
     * Menampilkan form input paket soal
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        // Get data tes
        $tes = Tes::find($id);

        // Jika tidak ada data tes
        if(!$tes){
            abort(404);
        }

        // View
        if(Auth::user()->role == 1){
            return view('paket-soal/admin/create', ['tes' => $tes]);
        }
        elseif(Auth::user()->role == 2){
            return view('paket-soal/hrd/create', ['tes' => $tes]);
        }
    }

    /**
     * Menyimpan paket soal...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        // Pesan Error
        $messages = [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute wajib dengan nomor atau angka.',
            'unique' => ':attribute sudah ada.',
            'email' => ':attribute wajib menggunakan format email.',
            'min' => ':attribute harus diisi minimal :min karakter.',
            'max' => ':attribute harus diisi maksimal :max karakter.',
        ];

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_paket' => 'required',
            'jumlah_soal' => 'required|numeric',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $paket = new PaketSoal;
            $paket->id_tes = $id;
            $paket->nama_paket = $request->nama_paket;
            $paket->jumlah_soal = $request->jumlah_soal;
            $paket->status = 0;
            $paket->save();
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('admin/tes/tipe/'.$id);
        }
        elseif(Auth::user()->role == 2){
            return redirect('hrd/tes/tipe/'.$id);
        }
    }

    /**
     * Menampilkan form edit paket soal
     *
     * int $id_tes
     * int $id_paket
     * @return \Illuminate\Http\Response
     */
    public function edit($id_tes, $id_paket)
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

        // View
        if(Auth::user()->role == 1){
            return view('paket-soal/admin/edit', [
                'paket' => $paket,
                'tes' => $tes,
            ]);
        }
        elseif(Auth::user()->role == 2){
            return view('paket-soal/hrd/edit', [
                'paket' => $paket,
                'tes' => $tes,
            ]);
        }
    }

    /**
     * Mengupdate paket soal...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Pesan Error
        $messages = [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute wajib dengan nomor atau angka.',
            'unique' => ':attribute sudah ada.',
            'email' => ':attribute wajib menggunakan format email.',
            'min' => ':attribute harus diisi minimal :min karakter.',
            'max' => ':attribute harus diisi maksimal :max karakter.',
        ];

        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_paket' => 'required',
            'jumlah_soal' => 'required|numeric',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Mengupdate data
            $paket = PaketSoal::find($request->id_paket);
            $paket->id_tes = $request->id_tes;
            $paket->nama_paket = $request->nama_paket;
            $paket->jumlah_soal = $request->jumlah_soal;
            $paket->save();
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('admin/tes/tipe/'.$id);
        }
        elseif(Auth::user()->role == 2){
            return redirect('hrd/tes/tipe/'.$id);
        }
    }

    /**
     * Mengupdate status paket soal...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        // Mengupdate status
        $paket = PaketSoal::find($request->id);
        $paket->status = $request->status;

        // Mengupdate status paket lain
        $another_paket = PaketSoal::where('id_paket','!=',$request->id)->where('id_tes','=',$id)->get();
        foreach($another_paket as $data){
            $data->status = 0;
            $data->save();
        }

        if($paket->save()){
            echo "Berhasil mengupdate status!";
        }
    }

    /**
     * Menghapus paket soal...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        // Menghapus data paket soal
        $paket = PaketSoal::find($request->id);

        // Menghapus data soal
        $soal = Soal::where('id_paket','=',$request->id)->get();
        foreach($soal as $data){
            $data->delete();
        }

        if($paket->delete()){
            echo "Berhasil menghapus data!";
        }
    }
}
