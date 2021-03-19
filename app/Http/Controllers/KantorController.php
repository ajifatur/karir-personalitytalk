<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\HRD;
use App\Kantor;
use App\Karyawan;
use App\User;

class KantorController extends Controller
{
    /**
     * Menampilkan data kantor
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	// Get data kantor
        if(Auth::user()->role == role_admin()){
            if($request->query('hrd') != null){
                $hrd = HRD::find($request->query('hrd'));
                $kantor = $hrd ? Kantor::join('hrd','kantor.id_hrd','=','hrd.id_hrd')->where('kantor.id_hrd','=',$request->query('hrd'))->get() : Kantor::join('hrd','kantor.id_hrd','=','hrd.id_hrd')->get();
            }
            else{
                $kantor = Kantor::join('hrd','kantor.id_hrd','=','hrd.id_hrd')->get();
            }
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $kantor = Kantor::join('hrd','kantor.id_hrd','=','hrd.id_hrd')->where('kantor.id_hrd','=',$hrd->id_hrd)->get();
        }

    	// View
    	return view('kantor/index', [
    		'kantor' => $kantor,
    	]);
    }

    /**
     * Menampilkan form input kantor
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get data HRD
        $hrd = HRD::all();

        // View
        return view('kantor/create', [
            'hrd' => $hrd
        ]);
    }

    /**
     * Menyimpan data kantor...
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
            'nama_kantor' => $request->nama_kantor == 'Head Office' ? 'required|unique:kantor' : 'required',
            'hrd' => Auth::user()->role == role_admin() ? 'required' : '',
            'telepon_kantor' => 'numeric'
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $kantor = new Kantor;
            $kantor->id_hrd = isset($hrd) ? $hrd->id_hrd : $request->hrd;
            $kantor->nama_kantor = $request->nama_kantor;
            $kantor->alamat_kantor = $request->alamat_kantor != '' ? $request->alamat_kantor : '';
            $kantor->telepon_kantor = $request->telepon_kantor != '' ? $request->telepon_kantor : '';
            $kantor->save();
        }

        // Redirect
        return redirect('admin/kantor')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Menampilkan form edit kantor
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	// Get data HRD dan kantor
    	if(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $kantor = Kantor::where('id_kantor','=',$id)->where('id_hrd','=',$hrd->id_hrd)->first();
        }
        else{
            $kantor = Kantor::find($id);
        }

        // Jika tidak ada data kantor
        if(!$kantor){
            abort(404);
        }

        // View
        return view('kantor/edit', [
        	'kantor' => $kantor,
        ]);
    }

    /**
     * Mengupdate data kantor...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		// Data kantor
        $kantor = Kantor::find($request->id);
		
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_kantor' => $kantor->nama_kantor != 'Head Office' ? [
                'required',
                Rule::unique('kantor')->ignore($request->id, 'id_kantor'),
            ] : '',
            'telepon_kantor' => 'numeric'
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Mengupdate data
            $kantor = Kantor::find($request->id);
            $kantor->nama_kantor = $request->nama_kantor;
            $kantor->alamat_kantor = $request->alamat_kantor != '' ? $request->alamat_kantor : '';
            $kantor->telepon_kantor = $request->telepon_kantor != '' ? $request->telepon_kantor : '';
            $kantor->save();
        }

        // Redirect
        return redirect('admin/kantor')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Menghapus data kantor...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $kantor = Kantor::find($request->id);
        $kantor->delete();

        // Redirect
        return redirect('admin/kantor')->with(['message' => 'Berhasil menghapus data.']);
    }
}
