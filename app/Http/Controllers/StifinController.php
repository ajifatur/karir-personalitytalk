<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Stifin;
use App\StifinTest;
use App\HRD;

class StifinController extends Controller
{
    /**
     * Menampilkan data tes stifin
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Akses
        if(!stifin_access()) abort(404);

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
        // Akses
        if(!stifin_access()) abort(404);

    	// Get data HRD
    	$hrd = HRD::all();

        // STIFIn Tests
        $tests = StifinTest::all();

        // View
        if(Auth::user()->role == role_admin()){
            return view('stifin/create', [
                'hrd' => $hrd,
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
    	// Get data HRD
    	if(Auth::user()->role == role_admin()){
            $hrd = HRD::find($request->hrd);
        }
    	if(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }

        // Validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'test' => 'required',
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
            $stifin = new Stifin;
            $stifin->name = $request->name;
            $stifin->test = $request->test;
            $stifin->hrd_id = isset($hrd) ? $hrd->id_hrd : $request->hrd;
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
        // Akses
        if(!stifin_access()) abort(404);

        // Get data stifin
        $stifin = Stifin::findOrFail($id);

    	// Get data HRD
    	$hrd = HRD::all();

        // STIFIn Tests
        $tests = StifinTest::all();

        // View
        if(Auth::user()->role == role_admin()){
            return view('stifin/edit', [
                'stifin' => $stifin,
                'hrd' => $hrd,
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

    /**
     * Mencetak data stifin
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        // Akses
        if(!stifin_access()) abort(404);

        // Get data stifin
        $stifin = Stifin::findOrFail($id);

        // View
        if(Auth::user()->role == role_admin()){
            return view('stifin/print', [
                'stifin' => $stifin,
            ]);
        }
        else{
            return view('error/404');
        }
    }
}