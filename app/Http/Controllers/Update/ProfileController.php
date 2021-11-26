<?php

namespace App\Http\Controllers\Update;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
            'name' => 'required|min:3|max:255',
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => [
                'required', 'email',
                Rule::unique('users')->ignore($request->id, 'id_user'),
            ],
            'username' => [
                'required', 'string', 'min:4',
                Rule::unique('users')->ignore($request->id, 'id_user'),
            ],
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the user
            $user = User::find($request->id);
            $user->nama_user = $request->name;
            $user->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $user->jenis_kelamin = $request->gender;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->save();

            // Update the HRD
            if(Auth::user()->role == role('hrd')) {
                $hrd = HRD::where('id_user','=',$user->id_user)->first();
                $hrd->nama_lengkap = $request->name;
                $hrd->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
                $hrd->jenis_kelamin = $request->gender;
                $hrd->email = $request->email;
                $hrd->save();
            }

            // Redirect
            return redirect()->route('admin.profile.edit')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Show the form for editing the specified resource's password.
     *
     * @return \Illuminate\Http\Response
     */
    public function editPassword()
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // View
        return view('admin/profile/edit-password');
    }

    /**
     * Update the specified resource's password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:new_password',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Check password hashing, for security
            if(Hash::check($request->old_password, Auth::user()->password)) {
                // Update the user password
                $user = User::find($request->id);
                $user->password = bcrypt($request->new_password);
                $user->save();

                // Redirect
                return redirect()->route('admin.profile.edit-password')->with(['message' => 'Berhasil mengupdate data.', 'status' => 1]);
            }
            else {
                // Redirect
                return redirect()->route('admin.profile.edit-password')->with(['message' => 'Kata sandi lama yang dimasukkan tidak cocok dengan kata sandi yang dimiliki saat ini.', 'status' => 0]);
            }
        }
    }
}