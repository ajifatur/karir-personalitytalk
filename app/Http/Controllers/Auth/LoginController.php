<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Ajifatur\FaturHelper\Models\Visitor;

class LoginController extends \App\Http\Controllers\Controller
{
    /**
     * Show login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // View
        return view('auth/login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:6',
            'password' => 'required|string|min:6',
        ]);

        // Return if has errors
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Check login type
            $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
            // Set credentials
            $credentials = [
                $loginType => $request->username,
                'password' => $request->password,
                // 'has_access' => 1
            ];

            // Auth attempt
            if(Auth::attempt($credentials)) {
                // Regenerate session
                $request->session()->regenerate();

                // Update user's last visit
                $user = User::find($request->user()->id);
                if($user) {
                    $user->last_visit = date('Y-m-d H:i:s');
                    $user->save();
                }
				
                // Add to visitors
                if(Schema::hasTable('visitors')) {
                    $visitor = new Visitor;
                    $visitor->user_id = $user->id;
                    $visitor->ip_address = $request->ip();
                    $visitor->device = device_info();
                    $visitor->browser = browser_info();
                    $visitor->platform = platform_info();
                    $visitor->location = location_info($request->ip());
                    $visitor->save();
                }

                // Redirect
                return redirect()->route('admin.dashboard');
            }
            else {
                return redirect()->back()->withErrors([
                    'message' => 'Tidak ada akun yang cocok dengan username / password yang Anda masukkan!'
                ])->withInput();
            }
        }
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect()->route('auth.login');
    }
}
