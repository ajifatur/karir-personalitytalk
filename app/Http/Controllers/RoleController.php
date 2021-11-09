<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Menampilkan data role
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	// Get data role
    	$role = Role::all();

    	// View
    	return view('role/admin/index', [
    		'role' => $role,
    	]);
    }

    /**
     * Menampilkan form input role
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // View
        return view('role/admin/create');
    }

    /**
     * Menyimpan role...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            'nama_role' => 'required',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $role = new Role;
            $role->nama_role = $request->nama_role;
            $role->save();
        }

        // Redirect
        return redirect('admin/role');
    }

    /**
     * Menampilkan form edit role
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get data role
        $role = Role::find($id);

        // Jika tidak ada data
        if(!$role){
            abort(404);
        }

        // View
        return view('role/admin/edit', ['role' => $role]);
    }

    /**
     * Mengupdate role...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
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
            'nama_role' => 'required',
        ], $messages);
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $role = Role::find($request->id_role);
            $role->nama_role = $request->nama_role;
            $role->save();
        }

        // Redirect
        return redirect('admin/role');
    }
}
