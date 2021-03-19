<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Tes;

class TesController extends Controller
{
    /**
     * Menampilkan data tes
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	// Get data tes
    	$tes = Tes::all();

    	// View
        if(Auth::user()->role == role_admin()){
        	return view('tes/index', [
        		'tes' => $tes,
        	]);
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Menampilkan form input tes
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // View
        if(Auth::user()->role == role_admin()){
            return view('tes/create');
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Menyimpan tes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_tes' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Permalink
            $permalink = generate_permalink($request->nama_tes);
            $i = 1;
            while(count_existing_data('tes', 'path', $permalink, 'id_tes', null) > 0){
                $permalink = rename_permalink(generate_permalink($request->nama_tes), $i);
                $i++;
            }

            // Menambah data
            $tes = new Tes;
            $tes->nama_tes = $request->nama_tes;
            $tes->path = $permalink;
            $tes->save();
        }

        // Redirect
        return redirect('admin/tes')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Menampilkan form edit tes
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get data tes
        $tes = Tes::find($id);

        // Jika tidak ada data
        if(!$tes){
            abort(404);
        }

        // View
        if(Auth::user()->role == role_admin()){
            return view('tes/edit', [
				'tes' => $tes,
			]);
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Mengupdate tes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_tes' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Permalink
            $permalink = generate_permalink($request->nama_tes);
            $i = 1;
            while(count_existing_data('tes', 'path', $permalink, 'id_tes', null) > 0){
                $permalink = rename_permalink(generate_permalink($request->nama_tes), $i);
                $i++;
            }

			$waktu_tes = '';
			if($request->tanggal != '' && $request->jam != ''){
				$waktu_tes = generate_date_format($request->tanggal, 'y-m-d').' '.$request->jam.':00';
			}
			
            // Mengupdate data
            $tes = Tes::find($request->id);
            $tes->nama_tes = $request->nama_tes;
            $tes->path = $permalink;
            $tes->waktu_tes = $waktu_tes;
            $tes->save();
        }

        // Redirect
        return redirect('admin/tes')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Menghapus tes
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $tes = Tes::find($request->id);
        $tes->delete();

        // Redirect
        return redirect('admin/tes')->with(['message' => 'Berhasil menghapus data.']);
    }
    
    /**
     * Generate url path...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generatePath(Request $request)
    {
        // Generate
        $path = generate_path_url($request->name);
        
        // Mengecek apakah ada path yang sama
        $countTes = Tes::where('path','like',$path.'%')->where('id_tes','!=',$request->id)->get();
        if(count($countTes) > 0){
            $count = count($countTes) + 1;
            $path = $path.'-'.$count;
        }
        else{
            $path = $path;
        }
        
        // Tampilkan
        echo $path;
    }
}
