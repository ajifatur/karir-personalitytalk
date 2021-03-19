<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Agama;

class AgamaController extends Controller
{
    /**
     * Menampilkan data agama
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	// Get data agama
        if(Auth::user()->role == role_admin()){
    	   $agama = Agama::all();

    	   // View
        	return view('agama/index', [
        		'agama' => $agama,
        	]);
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Menampilkan form input agama
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // View
        if(Auth::user()->role == role_admin()){
            return view('agama/create');
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Menyimpan agama...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_agama' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $agama = new Agama;
            $agama->nama_agama = $request->nama_agama;
            $agama->save();
        }

        // Redirect
        return redirect('admin/agama')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Menampilkan form edit agama
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get data agama
        $agama = Agama::find($id);

        // Jika tidak ada data
        if(!$agama){
            abort(404);
        }

        // View
        if(Auth::user()->role == role_admin()){
            return view('agama/edit', ['agama' => $agama]);
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Mengupdate agama
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_agama' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $agama = Agama::find($request->id);
            $agama->nama_agama = $request->nama_agama;
            $agama->save();
        }

        // Redirect
        return redirect('admin/agama')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Menghapus agama
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $agama = Agama::find($request->id);
        $agama->delete();

        // Redirect
        return redirect('admin/agama')->with(['message' => 'Berhasil menghapus data.']);
    }
}
