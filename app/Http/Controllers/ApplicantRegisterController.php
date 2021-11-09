<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ApplicantMail;
use App\Mail\HRDMail;
use App\Providers\RouteServiceProvider;
use App\Models\Agama;
use App\Models\HRD;
use App\Models\Lowongan;
use App\Models\Pelamar;
use App\Models\Posisi;
use App\Models\Temp;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class ApplicantRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Applicant Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    | Title:
    | Step 1: Form Identitas
    | Step 2: Form Upload Pas Foto
    | Step 3: Form Upload Foto Ijazah
    | Step 4: Form Data Darurat
    | Step 5: Form Data Keahlian
    |
    | Step 1: Nama Lengkap, Email, Tempat Lahir, Tanggal Lahir, Jenis Kelamin, Agama, Akun Sosial Media, Nomor HP, Nomor Telepon, Nomor KTP, Alamat, Alamat, Status Hubungan, Pendidikan Terakhir, Riwayat Pekerjaan
    | Step 2: Pas Foto
    | Step 3: Foto Ijazah
    | Step 4: Nama Orang Tua, Nomor HP Orang Tua, Alamat Orang Tua, Pekerjaan Orang Tua
    | Step 5: Keahlian
    |
    */

    use RegistersUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationFormStep1($code)
    {
        // Check session
        // $url_form = Session::get('url');
        $email = Session::get('email');

        // Jika tidak ada session url
        // if($url_form == null){
    	// 	$this->removePhotoAndSession();
        //     abort(404);
        // }

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp){
            $array = array();
        }
        else{
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_1', $array) ? $array['step_1'] : array();
        }

    	// Set variable
    	$step = 1;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);
    	$truePreviousPath = 'step-2';
    	$currentPath = 'step-1';

        // Data agama
        $agama = Agama::all();

    	// Delete session
    	if(!is_int(strpos($previousPath, $truePreviousPath)) && !is_int(strpos($previousPath, $currentPath))){
    		$this->removePhotoAndSession();
	    }

        return view('auth/register-step-1', [
            'agama' => $agama,
            'array' => $array,
        	'previousPath' => $previousPath,
        	'truePreviousPath' => $truePreviousPath,
            'step' => $step,
            // 'url_form' => $url_form,
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
            'nama_lengkap' => 'required|min:3|max:255',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'email' => 'required|email',
            'nomor_hp' => 'required',
            'alamat' => 'required',
            'pendidikan_terakhir' => 'required',
            'akun_sosmed' => 'required',
            'status_hubungan' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Set array step 1
            $array = $request->all();
            unset($array['_token']);
            unset($array['url']);
            foreach($array as $key=>$value){
                $array[$key] = $value == null ? '' : $value;
            }

            // Simpan ke temp
            $temp = Temp::where('email','=',$request->email)->first();
            if(!$temp){
                $temp = new Temp;
                $array = array('step_1' => $array);
                $temp->json = json_encode($array);
            }
            else{
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
        // return redirect('applicant/register/step-2');
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
        // $url_form = Session::get('url');
        // $url_form = $code;
    	$step = 2;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp){
            $array = array();
        }
        else{
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_2', $array) ? $array['step_2'] : array();
        }

    	// Delete session
  //   	if(!is_int(strpos($previousPath, 'step-')) || !array_key_exists('step_1', $array)){
  //   		$this->removePhotoAndSession();
  //   		// return redirect('applicant/register/step-1');
  //           return redirect('/lowongan/'.$code.'/daftar/step-1');
		// }

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
            // 'file_pas_foto' => $request->pas_foto == null ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '',
            'file_pas_foto' => $request->pas_foto == '' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            $request->session()->put('url', $request->url);
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Upload pas foto
            $file_pas_foto = $request->file('file_pas_foto');
            $file_name_pas_foto = '';
            if(!empty($file_pas_foto)){
                $destination_dir = 'assets/images/pas-foto/';
                $file_name_pas_foto = time().'.'.$file_pas_foto->getClientOriginalExtension();
                $file_pas_foto->move($destination_dir, $file_name_pas_foto);
            }
            
            // Simpan ke temp
            $temp = Temp::where('email','=',Session::get('email'))->first();
            $array = json_decode($temp->json, true);
            $array['step_2'] = array(
                'pas_foto' => empty($file_pas_foto) ? array_key_exists('step_2', $array) ? $array['step_2']['pas_foto'] : '' : $file_name_pas_foto,
            );
            $temp->json = json_encode($array);
            $temp->save();
        }

        // Redirect
        // return redirect('applicant/register/step-3');
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
        // $url_form = Session::get('url');
    	$step = 3;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp){
            $array = array();
        }
        else{
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_3', $array) ? $array['step_3'] : array();
        }

    	// Delete session
  //   	if(!is_int(strpos($previousPath, 'step-'))){
  //   		$this->removePhotoAndSession();
  //   		// return redirect('applicant/register/step-1');
  //           return redirect('/lowongan/'.$code.'/daftar/step-1');
		// }

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
            // 'file_foto_ijazah' => $request->foto_ijazah == null ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '',
            'file_foto_ijazah' => $request->foto_ijazah == '' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : '',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            $request->session()->put('url', $request->url);
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Upload foto ijazah
            $file_foto_ijazah = $request->file('file_foto_ijazah');
            $file_name_foto_ijazah = '';
            if(!empty($file_foto_ijazah)){
                $destination_dir = 'assets/images/foto-ijazah/';
                $file_name_foto_ijazah = time().'.'.$file_foto_ijazah->getClientOriginalExtension();
                $file_foto_ijazah->move($destination_dir, $file_name_foto_ijazah);
            }
            
            // Simpan ke temp
            $temp = Temp::where('email','=',Session::get('email'))->first();
            $array = json_decode($temp->json, true);
            $array['step_3'] = array(
                'foto_ijazah' => empty($file_foto_ijazah) ? array_key_exists('step_3', $array) ? $array['step_3']['foto_ijazah'] : '' : $file_name_foto_ijazah,
            );
            $temp->json = json_encode($array);
            $temp->save();
        }

        // Redirect
        // return redirect('applicant/register/step-4');
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
        // $url_form = Session::get('url');
    	$step = 4;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);
    	$truePreviousPath = 'step-3';
    	$currentPath = 'step-4';

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp){
            $array = array();
        }
        else{
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_4', $array) ? $array['step_4'] : array();
        }

    	// Delete session
  //   	if(!is_int(strpos($previousPath, 'step-'))){
  //   		$this->removePhotoAndSession();
  //   		// return redirect('applicant/register/step-1');
  //           return redirect('/lowongan/'.$code.'/daftar/step-1');
		// }

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
            'nama_orang_tua' => 'required|min:3|max:255',
            'nomor_hp_orang_tua' => 'required',
            'alamat_orang_tua' => 'required',
            'pekerjaan_orang_tua' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            $request->session()->put('url', $request->url);
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Set array step 4
            $post = $request->all();
            unset($post['_token']);
            unset($post['url']);
            foreach($post as $key=>$value){
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
        // return redirect('applicant/register/step-5');
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
        // $url_form = Session::get('url');
    	$step = 5;
    	$previousURL = URL::previous();
    	$previousURLArray = explode('/', $previousURL);
    	$previousPath = end($previousURLArray);
    	$truePreviousPath = 'step-4';
    	$currentPath = 'step-5';
    	
    	// Keahlian dari posisi lowongan
    	$lowongan = Lowongan::where('url_lowongan','=',$code)->first();
    	$posisi = Posisi::find($lowongan->posisi);
    	$keahlian = explode(',', $posisi->keahlian);

        // Get data temp
        $temp = Temp::where('email','=',$email)->first();
        if(!$temp){
            $array = array();
        }
        else{
            $array = json_decode($temp->json, true);
            $array = array_key_exists('step_5', $array) ? $array['step_5'] : array();
        }

    	// Delete session
  //   	if(!is_int(strpos($previousPath, $truePreviousPath)) && !is_int(strpos($previousPath, $currentPath))){
  //   		$this->removePhotoAndSession();
  //   		// return redirect('applicant/register/step-1');
  //           return redirect('/lowongan/'.$code.'/daftar/step-1');
		// }

        return view('auth/register-step-5', [
            'array' => $array,
            'keahlian' => $keahlian,
        	'previousPath' => $previousPath,
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
            'keahlian.*.skor' => 'required'
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            $request->session()->put('url', $request->url);
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Ambil data lowongan
            $lowongan = Lowongan::where('url_lowongan','=',$request->url)->first();
            $data_hrd = HRD::find($lowongan->id_hrd);
            
            // Generate username
            $data_user = User::where('has_access','=',0)->where('username','like', $data_hrd->kode.'%')->latest()->first();
            if(!$data_user){
                $username = generate_username(null, $data_hrd->kode);
            }
            else{
                $username = generate_username($data_user->username, $data_hrd->kode);
            }
            
            // Set array step 5
            $keahlian = $request->get('keahlian');

            // Simpan ke temp
            $temp = Temp::where('email','=',Session::get('email'))->first();
            $array = json_decode($temp->json, true);
            $array['step_5'] = $keahlian;
            $temp->json = json_encode($array);
            $temp->save();

            // Mengambil data temp
            $temp_data = Temp::where('email','=',Session::get('email'))->first();
            $temp_array = json_decode($temp_data->json, true);

            // Menambah akun pelamar
            $applicant = new User;
            $applicant->nama_user = $temp_array['step_1']['nama_lengkap'];
            $applicant->tanggal_lahir = generate_date_format($temp_array['step_1']['tanggal_lahir'], 'y-m-d');
            $applicant->jenis_kelamin = $temp_array['step_1']['jenis_kelamin'];
            $applicant->email = $temp_array['step_1']['email'];
            $applicant->username = $username;
            $applicant->password = bcrypt($username);
            $applicant->password_str = $username;
            $applicant->foto = '';
            $applicant->role = role_pelamar();
            $applicant->has_access = 0;
            $applicant->status = 1;
            $applicant->last_visit = date("Y-m-d H:i:s");
            $applicant->created_at = date("Y-m-d H:i:s");
            $applicant->save();

            // Ambil data akun pelamar
            $akun = User::where('username','=',$applicant->username)->first();

            // Menambah data pelamar
            $pelamar = new Pelamar;
            $pelamar->id_hrd = $lowongan->id_hrd;
            $pelamar->nama_lengkap = $temp_array['step_1']['nama_lengkap'];
            $pelamar->tempat_lahir = $temp_array['step_1']['tempat_lahir'];
            $pelamar->tanggal_lahir = generate_date_format($temp_array['step_1']['tanggal_lahir'], 'y-m-d');
            $pelamar->jenis_kelamin = $temp_array['step_1']['jenis_kelamin'];
            $pelamar->agama = $temp_array['step_1']['agama'];
            $pelamar->email = $temp_array['step_1']['email'];
            $pelamar->nomor_hp = $temp_array['step_1']['nomor_hp'];
            $pelamar->alamat = $temp_array['step_1']['alamat'];
            $pelamar->pendidikan_terakhir = $temp_array['step_1']['pendidikan_terakhir'];
            $pelamar->nomor_ktp = $temp_array['step_1']['nomor_ktp'];
            $pelamar->nomor_telepon = $temp_array['step_1']['nomor_telepon'];
            $pelamar->status_hubungan = $temp_array['step_1']['status_hubungan'];
            $pelamar->kode_pos = '';
            $pelamar->data_darurat = json_encode($temp_array['step_4']);
            $pelamar->akun_sosmed = json_encode(array($temp_array['step_1']['sosmed'] => $temp_array['step_1']['akun_sosmed']));
            $pelamar->pendidikan_formal = '';
            $pelamar->pendidikan_non_formal = '';
            $pelamar->riwayat_pekerjaan = $temp_array['step_1']['riwayat_pekerjaan'];
            $pelamar->keahlian = json_encode($temp_array['step_5']);;
            $pelamar->pertanyaan = '';
            $pelamar->pas_foto = array_key_exists('step_2', $temp_array) ? $temp_array['step_2']['pas_foto'] : '';
            $pelamar->foto_ijazah = array_key_exists('step_3', $temp_array) ? $temp_array['step_3']['foto_ijazah'] : '';
            $pelamar->id_user = $akun->id_user;
            $pelamar->posisi = $lowongan->id_lowongan;
            $pelamar->pelamar_at = date("Y-m-d H:i:s");
            $pelamar->save();

            // Ambil data akun pelamar
            $akun_pelamar = Pelamar::where('email','=',$applicant->email)->first();

            // Send Mail to HRD
            $hrd = HRD::find($lowongan->id_hrd);
            Mail::to($hrd->email)->send(new HRDMail($akun_pelamar->id_pelamar));

            // Send Mail to Pelamar
            Mail::to($applicant->email)->send(new ApplicantMail($akun_pelamar->id_pelamar));

            // Remove session
            $this->removeSession();
        }

        // View
        return view('auth/success');
    }

    /**
     * Mengirim email bahwa ada pelamar ke HRD
     *
     * @return void
     */
    public function sendMailToHRD()
    {
        Mail::to("ajifatur14@students.unnes.ac.id")->send(new ApplicantMail(33));
 
        return "Email telah dikirim";
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/applicant';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    // Remove file
    public function removeFile($dir, $filename){
    	File::delete($dir.$filename);
    }

    // Remove session
    public function removeSession(){
        // Get data temp
        $temp = Temp::where('email','=',Session::get('email'))->first();

        // If temp is exist
        if($temp != null){
            // Delete data temp
            $temp->delete();
        }

        // Remove session
        // Session::forget('url');
        Session::forget('email');
    }

    // Remove photo and session
    public function removePhotoAndSession(){
        // Get data temp
        $temp = Temp::where('email','=',Session::get('email'))->first();

        // If temp is exist
        if($temp != null){
            // Convert json to array
            $array = json_decode($temp->json, true);

        	// Remove file first before remove session
        	if(array_key_exists('step_2', $array)){
            	$this->removeFile('assets/images/pas-foto/', $array['step_2']['pas_foto']);
            	$this->removeFile('assets/images/foto-ijazah/', $array['step_2']['foto_ijazah']);
        	}

            // Delete data temp
            // $temp->delete();
        }

    	// And then remove session
        // Session::forget('url');
    	Session::forget('email');
    }
}
