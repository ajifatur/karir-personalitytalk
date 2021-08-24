<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Stifin;
use App\StifinTest;

class StifinController extends Controller
{
    /**
     * Menampilkan data tes stifin
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	// Get data stifin
        if(Auth::user()->role == role_admin()){
    	   $stifin = Stifin::all();

    	   // View
        	return view('stifin/index', [
        		'stifin' => $stifin,
        	]);
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Menampilkan form input stifin
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // STIFIn Tests
        $tests = StifinTest::all();

        // View
        if(Auth::user()->role == role_admin()){
            return view('stifin/create', [
                'tests' => $tests
            ]);
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Menyimpan stifin...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'test' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $stifin = new Stifin;
            $stifin->name = $request->name;
            $stifin->test = $request->test;
            $stifin->birthdate = $request->birthdate != '' ? generate_date_format($request->birthdate, 'y-m-d') : null;
            $stifin->test_at = $request->test_at != '' ? generate_date_format($request->test_at, 'y-m-d') : null;
            $stifin->save();
        }

        // Redirect
        return redirect('admin/stifin')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Menampilkan form edit stifin
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get data stifin
        $stifin = Stifin::findOrFail($id);

        // STIFIn Tests
        $tests = StifinTest::all();

        // View
        if(Auth::user()->role == role_admin()){
            return view('stifin/edit', [
                'stifin' => $stifin,
                'tests' => $tests,
            ]);
        }
        else{
            return view('error/404');
        }
    }

    /**
     * Mengupdate stifin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'test' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Mengupdate data
            $stifin = Stifin::find($request->id);
            $stifin->name = $request->name;
            $stifin->test = $request->test;
            $stifin->birthdate = $request->birthdate != '' ? generate_date_format($request->birthdate, 'y-m-d') : null;
            $stifin->test_at = $request->test_at != '' ? generate_date_format($request->test_at, 'y-m-d') : null;
            $stifin->save();
        }

        // Redirect
        return redirect('admin/stifin')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Menghapus stifin
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $stifin = Stifin::find($request->id);
        $stifin->delete();

        // Redirect
        return redirect('admin/stifin')->with(['message' => 'Berhasil menghapus data.']);
    }
}
