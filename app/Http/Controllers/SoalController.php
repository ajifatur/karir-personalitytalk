<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Exports\SoalExport;
use App\Imports\SoalImport;
use Maatwebsite\Excel\Facades\Excel;
use App\PaketSoal;
use App\Soal;
use App\Tes;


class SoalController extends Controller
{
    /**
     * Menampilkan data soal
     * 
     * int $id_paket
     * int $id_tes
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

    	// Get data soal
    	$soal = Soal::where('id_paket','=',$id_paket)->orderBy('nomor','asc')->get();
        foreach($soal as $data){
        	$array = json_decode($data->soal, true);
        	$data->soal = $array;
        }

    	// View
        if(Auth::user()->role == 1){
        	return view('paket-soal/admin/detail', [
                'paket' => $paket,
        		'soal' => $soal,
                'tes' => $tes,
                'id_paket' => $id_paket,
                'id_tes' => $id_tes,
        	]);
        }
        elseif(Auth::user()->role == 2){
            return view('paket-soal/hrd/detail', [
                'paket' => $paket,
                'soal' => $soal,
                'tes' => $tes,
                'id_paket' => $id_paket,
                'id_tes' => $id_tes,
            ]);
        }
    }

    /**
     * Menampilkan form input soal
     *
     * int $id_paket
     * int $id_tes
     * @return \Illuminate\Http\Response
     */
    public function create($id_tes, $id_paket)
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

    	// Get data soal
    	$soal = Soal::where('id_paket','=',$id_paket)->orderBy('nomor','asc')->get();
    	$no_soal = array();
        foreach($soal as $data){
        	$array = json_decode($data->soal, true);
        	$data->soal = $array;
        	array_push($no_soal, $data->nomor);
        }

        // Jika jumlah soal sudah sesuai dengan jumlah pada paket
        if(count($soal) == $paket->jumlah_soal){
            abort(404);
        }

        // Menambahkan nomor soal yang kosong
        $jumlah_soal = $paket->jumlah_soal;
        $i = 1;
        $no_soal_tersedia = array();
        while($i <= $jumlah_soal){
        	if(!in_array($i, $no_soal)){
        		array_push($no_soal_tersedia, $i);
        	}
        	$i++;
        }

    	// View
        if(Auth::user()->role == 1){
        	return view('soal/admin/create', [
        		'tes' => $tes,
        		'no_soal_tersedia' => $no_soal_tersedia,
                'soal' => $soal,
                'id_paket' => $id_paket,
                'id_tes' => $id_tes,
        	]);
        }
        elseif(Auth::user()->role == 2){
            return view('soal/hrd/create', [
                'tes' => $tes,
                'no_soal_tersedia' => $no_soal_tersedia,
                'soal' => $soal,
                'id_paket' => $id_paket,
                'id_tes' => $id_tes,
            ]);
        }
    }

    /**
     * Menyimpan soal...
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
            'nomor' => 'required|numeric',
            'pilihan.*' => 'required',
            'disc.*' => 'required',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
        	// Menambah data
        	$soal = new Soal;
        	$soal->id_paket = $request->id_paket;
        	$soal->nomor = $request->nomor;
        	$soal->soal = json_encode(array(array('pilihan'=>$request->pilihan, 'disc'=>$request->disc)));
        	$soal->save();
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('admin/tes/tipe/'.$request->id_tes.'/paket/soal/'.$request->id_paket);
        }
        elseif(Auth::user()->role == 2){
            return redirect('hrd/tes/tipe/'.$request->id_tes.'/paket/soal/'.$request->id_paket);
        }
    }

    /**
     * Menampilkan form edit soal
     *
     * int $id_tes
     * int $id_soal
     * @return \Illuminate\Http\Response
     */
    public function edit($id_tes, $id_soal)
    {
        // Get data tes
        $tes = Tes::find($id_tes);

        // Jika tidak ada data tes
        if(!$tes){
            abort(404);
        }

        // Get data
        $soal = Soal::find($id_soal);

        // Jika tidak ada data
        if(!$soal){
            abort(404);
        }
        else{
            $soal->soal = json_decode($soal->soal, true);
        }


        // Paket dan kumpulan soal
        $paket = PaketSoal::find($soal->id_paket);
        // $kumpulan_soal = Soal::where('id_paket','=',$soal->id_paket)->orderBy('nomor','asc')->get();

        // Menambahkan nomor soal yang kosong
        $jumlah_soal = $paket->jumlah_soal;
        $i = 1;
        $no_soal_tersedia = array();
        while($i <= $jumlah_soal){
            array_push($no_soal_tersedia, $i);
            $i++;
        }

        // View
        if(Auth::user()->role == 1){
            return view('soal/admin/edit', [
                'tes' => $tes,
                'soal' => $soal,
                'no_soal_tersedia' => $no_soal_tersedia,
                'id_paket' => $soal->id_paket,
                'id_tes' => $id_tes,
            ]);
        }
        elseif(Auth::user()->role == 2){
            return view('soal/hrd/edit', [
                'tes' => $tes,
                'soal' => $soal,
                'no_soal_tersedia' => $no_soal_tersedia,
                'id_paket' => $soal->id_paket,
                'id_tes' => $id_tes,
            ]);
        }
    }

    /**
     * Mengupdate soal...
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
            'nomor' => 'required|numeric',
            'pilihan.*' => 'required',
            'disc.*' => 'required',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Jika nomor soal berubah, maka akan mengubah nomor soal yang bersangkutan juga
            if($request->nomor != $request->nomor_old){
                $soal2 = Soal::where('nomor','=',$request->nomor)->where('id_paket','=',$request->id_paket)->first();
                $soal2->nomor = $request->nomor_old;
                $soal2->save();
            }

            // Mengupdate data
            $soal = Soal::find($request->id);
            $soal->id_paket = $request->id_paket;
            $soal->nomor = $request->nomor;
            $soal->soal = json_encode(array(array('pilihan'=>$request->pilihan, 'disc'=>$request->disc)));
            $soal->save();
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('admin/tes/tipe/'.$request->id_tes.'/paket/soal/'.$request->id_paket);
        }
        elseif(Auth::user()->role == 2){
            return redirect('hrd/tes/tipe/'.$request->id_tes.'/paket/soal/'.$request->id_paket);
        }
    }

    /**
     * Menghapus soal...
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        // Menghapus data
        $soal = Soal::find($request->id);
        if($soal->delete()){
            echo "Berhasil menghapus data!";
        }
    }

    /**
     * Export excel
     *
     * int $id_tes
     * int $id_paket
     * @return \Illuminate\Http\Response
     */
    public function exportExcel($id_tes, $id_paket){
    	// Download excel
    	return Excel::download(new SoalExport($id_paket), 'soal.xlsx');
    }

    /**
     * Menampilkan form import excel
     *
     * int $id_tes
     * int $id_paket
     * @return \Illuminate\Http\Response
     */
    public function importForm($id_tes, $id_paket)
    {
        // Get data tes
        $tes = Tes::find($id_tes);

        // Jika tidak ada data tes
        if(!$tes){
            abort(404);
        }

    	// View
        if(Auth::user()->role == 1){
        	return view('soal/admin/import', [
                'tes' => $tes,
                'id_paket' => $id_paket
            ]);
        }
        elseif(Auth::user()->role == 2){
            return view('soal/hrd/import', [
                'tes' => $tes,
                'id_paket' => $id_paket
            ]);
        }
    }

    /**
     * Mengimport
     *
     * int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importExcel(Request $request, $id)
    {
        // Pesan Error
        $messages = [
            'required' => ':attribute wajib diisi.',
            'mimes' => 'Hanya bisa mengimport dengan file format .xls dan .xlsx'
        ];

        // Validasi
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
        	Excel::import(new SoalImport($request->id_paket), $request->file('file'));
        }

        // Redirect
        if(Auth::user()->role == 1){
            return redirect('admin/tes/tipe/'.$request->id_tes.'/paket/soal/'.$request->id_paket);
        }
        elseif(Auth::user()->role == 2){
            return redirect('hrd/tes/tipe/'.$request->id_tes.'/paket/soal/'.$request->id_paket);
        }
    }
}
