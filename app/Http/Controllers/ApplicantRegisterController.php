<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Mail\ApplicantMail;
use App\Mail\HRDMail;
use Ajifatur\Helpers\DateTimeExt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use App\Models\Vacancy;
use App\Models\Temp;
use App\Models\User;
use App\Models\UserAttachment;
use App\Models\UserAttribute;
use App\Models\UserGuardian;
use App\Models\UserSocmed;
use App\Models\UserSkill;

class ApplicantRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Applicant Register Controller
    |--------------------------------------------------------------------------
    |
    | Title:
    | Step 1: Form Identitas
    | Step 2: Form Upload Pas Foto
    | Step 3: Form Upload Foto Ijazah
    | Step 4: Form Data Darurat
    | Step 5: Form Data Keahlian
    |
    | Step 1: Nama Lengkap, Email, Tempat Lahir, Tanggal Lahir, Jenis Kelamin, Agama, Akun Sosial Media, Nomor HP, Nomor KTP, Alamat, Status Hubungan, Pendidikan Terakhir, Riwayat Pekerjaan
    | Step 2: Pas Foto
    | Step 3: Foto Ijazah
    | Step 4: Nama Orang Tua, Nomor HP Orang Tua, address Orang Tua, Pekerjaan Orang Tua
    | Step 5: Keahlian
    |
    */

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationFormStep1($code)
    {
        // Check session
        $email = Session::get('email');

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp) {
            $array =[];
        }
        else {
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_1', $array) ? $array['step_1'] : [];
        }

    	// Set variable
    	$step = 1;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);
    	$truePreviousPath = 'step-2';
    	$currentPath = 'step-1';

    	// Delete session
    	if(!is_int(strpos($previousPath, $truePreviousPath)) && !is_int(strpos($previousPath, $currentPath))) {
    		$this->removePhotoAndSession();
	    }

        return view('auth/register-step-1', [
            'array' => $array,
        	'previousPath' => $previousPath,
        	'truePreviousPath' => $truePreviousPath,
            'step' => $step,
            'url_form' => $code,
        ]);
    }

    /**
     * Validate and submit registration form step 1
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    **/
    public function submitRegistrationFormStep1(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'birthplace' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'address' => 'required',
            'latest_education' => 'required',
            'socmed' => 'required',
            'relationship' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()) {
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else {
            // Set array step 1
            $array = $request->all();
            unset($array['_token']);
            unset($array['url']);
            foreach($array as $key=>$value){
                $array[$key] = $value == null ? '' : $value;
            }

            // Simpan ke temp
            $temp = Temp::where('email','=',$request->email)->first();
            if(!$temp) {
                $temp = new Temp;
                $array = array('step_1' => $array);
                $temp->json = json_encode($array);
            }
            else {
                $json = json_decode($temp->json, true);
                $json['step_1'] = $array;
                $temp->json = json_encode($json);
            }
            $temp->email = $request->email;
            $temp->save();

        	// Simpan ke session
            $request->session()->put('email', $request->email);
            $request->session()->put('url', $request->url);
        }

        // Redirect
        return redirect('/lowongan/'.$request->url.'/daftar/step-2');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationFormStep2($code)
    {
    	// Set variable
        $email = Session::get('email');
    	$step = 2;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp) {
            $array = [];
        }
        else {
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_2', $array) ? $array['step_2'] : [];
        }

        return view('auth/register-step-2', [
            'array' => $array,
        	'previousPath' => $previousPath,
            'step' => $step,
            'url_form' => $code,
        ]);
    }

    /**
     * Validate and submit registration form step 2
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    **/
    public function submitRegistrationFormStep2(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'file_photo' => $request->photo == '' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()) {
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            $request->session()->put('url', $request->url);
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else {
            // Upload pas foto
            $file_photo = $request->file('file_photo');
            $file_name_photo = '';
            if(!empty($file_photo)) {
                $destination_dir = 'assets/images/pas-foto/';
                $file_name_photo = time().'.'.$file_photo->getClientOriginalExtension();
                $file_photo->move($destination_dir, $file_name_photo);
            }
            
            // Simpan ke temp
            $temp = Temp::where('email','=',Session::get('email'))->first();
            $array = json_decode($temp->json, true);
            $array['step_2'] = [
                'photo' => empty($file_photo) ? array_key_exists('step_2', $array) ? $array['step_2']['photo'] : '' : $file_name_photo,
            ];
            $temp->json = json_encode($array);
            $temp->save();
        }

        // Redirect
        return redirect('/lowongan/'.$request->url.'/daftar/step-3');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationFormStep3($code)
    {
    	// Set variable
        $email = Session::get('email');
    	$step = 3;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp) {
            $array = [];
        }
        else {
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_3', $array) ? $array['step_3'] : [];
        }

        return view('auth/register-step-3', [
            'array' => $array,
        	'previousPath' => $previousPath,
            'step' => $step,
            'url_form' => $code,
        ]);
    }

    /**
     * Validate and submit registration form step 3
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    **/
    public function submitRegistrationFormStep3(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'file_certificate' => $request->certificate == '' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()) {
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            $request->session()->put('url', $request->url);
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else {
            // Upload foto ijazah
            $file_certificate = $request->file('file_certificate');
            $file_name_certificate = '';
            if(!empty($file_certificate)) {
                $destination_dir = 'assets/images/foto-ijazah/';
                $file_name_certificate = time().'.'.$file_certificate->getClientOriginalExtension();
                $file_certificate->move($destination_dir, $file_name_certificate);
            }
            
            // Simpan ke temp
            $temp = Temp::where('email','=',Session::get('email'))->first();
            $array = json_decode($temp->json, true);
            $array['step_3'] = [
                'certificate' => empty($file_certificate) ? array_key_exists('step_3', $array) ? $array['step_3']['certificate'] : '' : $file_name_certificate,
            ];
            $temp->json = json_encode($array);
            $temp->save();
        }

        // Redirect
        return redirect('/lowongan/'.$request->url.'/daftar/step-4');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationFormStep4($code)
    {
    	// Set variable
        $email = Session::get('email');
    	$step = 4;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);
    	$truePreviousPath = 'step-3';
    	$currentPath = 'step-4';

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp) {
            $array = [];
        }
        else {
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_4', $array) ? $array['step_4'] : [];
        }

        return view('auth/register-step-4', [
            'array' => $array,
        	'previousPath' => $previousPath,
            'step' => $step,
            'url_form' => $code,
        ]);
    }

    /**
     * Validate and submit registration form step 4
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    **/
    public function submitRegistrationFormStep4(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'guardian_name' => 'required|min:3|max:255',
            'guardian_phone_number' => 'required|numeric',
            'guardian_address' => 'required',
            'guardian_occupation' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()) {
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            $request->session()->put('url', $request->url);
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else {
            // Set array step 4
            $post = $request->all();
            unset($post['_token']);
            unset($post['url']);
            foreach($post as $key=>$value) {
                $post[$key] = $value == null ? '' : $value;
            }

            // Simpan ke temp
            $temp = Temp::where('email','=',Session::get('email'))->first();
            $array = json_decode($temp->json, true);
            $array['step_4'] = $post;
            $temp->json = json_encode($array);
            $temp->save();
        }

        // Redirect
        return redirect('/lowongan/'.$request->url.'/daftar/step-5');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationFormStep5($code)
    {
    	// Set variable
        $email = Session::get('email');
    	$step = 5;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);
    	$truePreviousPath = 'step-4';
    	$currentPath = 'step-5';
    	
    	// Keahlian dari posisi lowongan
        $vacancy = Vacancy::has('company')->has('position')->where('code','=',$code)->first();
        $skills = $vacancy->position->skills;

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp) {
            $array = [];
        }
        else {
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_5', $array) ? $array['step_5'] : [];
        }

        return view('auth/register-step-5', [
            'array' => $array,
        	'previousPath' => $previousPath,
            'skills' => $skills,
            'step' => $step,
            'url_form' => $code,
        ]);
    }

    /**
     * Validate and submit registration form step 5
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    **/
    public function submitRegistrationFormStep5(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'skills.*.score' => 'required'
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()) {
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            $request->session()->put('url', $request->url);
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else {
            // Ambil data lowongan
            $vacancy = Vacancy::has('company')->has('position')->where('code','=',$request->url)->first();
            
            // Generate username
            $data_user = User::whereHas('attribute', function (Builder $query) use ($vacancy) {
                return $query->has('company')->has('position')->where('company_id','=',$vacancy->company_id);
            })->latest('username')->first();
            if(!$data_user)
                $username = generate_username(null, $vacancy->company->code);
            else
                $username = generate_username($data_user->username, $vacancy->company->code);
            
            // Set array step 5
            $skills = $request->get('skills');

            // Simpan ke temp
            $temp = Temp::where('email','=',Session::get('email'))->first();
            $array = json_decode($temp->json, true);
            $array['step_5'] = $skills;
            $temp->json = json_encode($array);
            $temp->save();
            $temp_array = json_decode($temp->json, true);

            // Save the user
            $user = new User;
            $user->role_id = role('applicant');
            $user->name = $temp_array['step_1']['name'];
            $user->email = $temp->email;
            $user->username = $username;
            $user->password = bcrypt($username);
            $user->access_token = null;
            $user->avatar = '';
            $user->status = 1;
            $user->last_visit = null;

            $user->tanggal_lahir = null;
            $user->jenis_kelamin = '';
            $user->password_str = '';
            $user->has_access = 0;
            $user->save();

            // Save the user attributes
            $user_attribute = new UserAttribute;
            $user_attribute->user_id = $user->id;
            $user_attribute->company_id = $vacancy->company->id;
            $user_attribute->office_id = 0;
            $user_attribute->position_id = $vacancy->position_id;
            $user_attribute->vacancy_id = $vacancy->id;
            $user_attribute->birthdate = DateTimeExt::change($temp_array['step_1']['birthdate']);
            $user_attribute->birthplace = $temp_array['step_1']['birthplace'];
            $user_attribute->gender = $temp_array['step_1']['gender'];
            $user_attribute->country_code = 'ID';
            $user_attribute->dial_code = '+62';
            $user_attribute->phone_number = $temp_array['step_1']['phone_number'];
            $user_attribute->address = $temp_array['step_1']['address'];
            $user_attribute->identity_number = $temp_array['step_1']['identity_number'];
            $user_attribute->religion = $temp_array['step_1']['religion'];
            $user_attribute->relationship = $temp_array['step_1']['relationship'];
            $user_attribute->latest_education = $temp_array['step_1']['latest_education'];
            $user_attribute->job_experience = $temp_array['step_1']['job_experience'];
            $user_attribute->start_date = null;
            $user_attribute->end_date = null;
            $user_attribute->save();

            // Save the user socmed
            $user_socmed = new UserSocmed;
            $user_socmed->user_id = $user->id;
            $user_socmed->platform = $temp_array['step_1']['platform'];
            $user_socmed->account = $temp_array['step_1']['socmed'];
            $user_socmed->save();

            // Save the user attachments
            $user_attachment_photo = new UserAttachment;
            $user_attachment_photo->user_id = $user->id;
            $user_attachment_photo->attachment_id = 1;
            $user_attachment_photo->file = $temp_array['step_2']['photo'];
            $user_attachment_photo->save();

            $user_attachment_certificate = new UserAttachment;
            $user_attachment_certificate->user_id = $user->id;
            $user_attachment_certificate->attachment_id = 2;
            $user_attachment_certificate->file = $temp_array['step_3']['certificate'];
            $user_attachment_certificate->save();

            // Save the user guardian
            $user_guardian = new UserGuardian;
            $user_guardian->user_id = $user->id;
            $user_guardian->name = $temp_array['step_4']['guardian_name'];
            $user_guardian->address = $temp_array['step_4']['guardian_address'];
            $user_guardian->country_code = 'ID';
            $user_guardian->dial_code = '+62';
            $user_guardian->phone_number = $temp_array['step_4']['guardian_phone_number'];
            $user_guardian->occupation = $temp_array['step_4']['guardian_occupation'];
            $user_guardian->save();

            // Save the user skills
            foreach($temp_array['step_5'] as $skill) {
                $user_skill = new UserSkill;
                $user_skill->user_id = $user->id;
                $user_skill->skill_id = $skill['id'];
                $user_skill->score = $skill['score'];
                $user_skill->save();
            }

            // Send Mail to HRD
            $hrd = User::find($vacancy->company->user->id);
            if($hrd)
                Mail::to($hrd->email)->send(new HRDMail($user->id));

            // // Send Mail to Pelamar
            Mail::to($user->email)->send(new ApplicantMail($user->id));

            // Remove session
            $this->removeSession();
        }

        // View
        return view('auth/success');
    }

    // Remove file
    public function removeFile($dir, $filename) {
    	File::delete($dir.$filename);
    }

    // Remove session
    public function removeSession() {
        // Get data temp
        $temp = Temp::where('email','=',Session::get('email'))->first();

        // If temp is exist
        if($temp != null) {
            // Delete data temp
            $temp->delete();
        }

        Session::forget('email');
    }

    // Remove photo and session
    public function removePhotoAndSession() {
        // Get data temp
        $temp = Temp::where('email','=',Session::get('email'))->first();

        // If temp is exist
        if($temp != null) {
            // Convert json to array
            $array = json_decode($temp->json, true);

        	// Remove file first before remove session
        	if(array_key_exists('step_2', $array)) {
            	$this->removeFile('assets/images/pas-foto/', $array['step_2']['photo']);
            	$this->removeFile('assets/images/foto-ijazah/', $array['step_3']['certificate']);
        	}

            // Delete data temp
            $temp->delete();
        }

    	// And then remove session
    	Session::forget('email');
    }
}
