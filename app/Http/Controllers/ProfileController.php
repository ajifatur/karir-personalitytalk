<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\User;

class ProfileController extends \App\Http\Controllers\Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail()
    {
        // Get the user
        $user = User::find(Auth::user()->id);

        // View
        return view('admin/profile/detail', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
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
                Rule::unique('users')->ignore($request->id, 'id'),
            ],
            'username' => [
                'required', 'string', 'min:4',
                Rule::unique('users')->ignore($request->id, 'id'),
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
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->save();

            // Update the user attributes
            $user->attribute->birthdate = DateTimeExt::change($request->birthdate);
            $user->attribute->gender = $request->gender;
            $user->attribute->save();

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