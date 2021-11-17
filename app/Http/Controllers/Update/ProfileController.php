<?php

namespace App\Http\Controllers\Update;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\HRD;

class ProfileController extends \App\Http\Controllers\Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail()
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Get the user
        $user = User::find(Auth::user()->id_user);

        if($user->role == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }

        // View
        return view('admin/profile/detail', [
            'user' => $user,
            'hrd' => Auth::user()->role == role('hrd') ? $hrd : null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // View
        return view('admin/profile/edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => $request->phone_number != '' ? 'numeric' : ''
        ], validationMessages());
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the office
            $office = Kantor::find($request->id);
            $office->nama_kantor = $request->name;
            $office->alamat_kantor = $request->address != '' ? $request->address : '';
            $office->telepon_kantor = $request->phone_number != '' ? $request->phone_number : '';
            $office->save();

            // Redirect
            return redirect()->route('admin.office.index')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the office
        $office = Kantor::find($request->id);

        // Delete the office
        $office->delete();

        // Redirect
        return redirect()->route('admin.office.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}