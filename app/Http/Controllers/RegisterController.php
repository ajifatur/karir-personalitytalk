<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PelamarExport;
use App\Models\Agama;
use App\Models\HRD;
use App\Models\Karyawan;
use App\Models\Lowongan;
use App\Models\Posisi;
use App\Models\Pelamar;
use App\Models\Seleksi;
use App\Models\User;

class RegisterController extends \App\Http\Controllers\Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function create($code)
    {

        // Get the vacancy
    	$vacancy = Lowongan::where('url_lowongan','=',$code)->first();

        // Get the position
    	$position = Posisi::find($vacancy->posisi);

        // Get skills
    	$skills = explode(',', $position->keahlian);

        return view('auth/register', [
            'url_form' => $code,
            'skills' => $skills,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'birthdate' => 'required',
            'birthplace' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'platform' => 'required',
            'socmed' => 'required',
            'address' => 'required',
            'latest_education' => 'required',
            'relationship' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'certificate' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'guardian_name' => 'required|min:3|max:255',
            'guardian_phone_number' => 'required|numeric',
            'guardian_address' => 'required',
            'guardian_occupation' => 'required',
            'skills.*.score' => 'required'
        ], validationMessages());
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput($request->only([
                'name',
                'birthdate',
                'birthplace',
                'gender',
                'religion',
                'email',
                'phone_number',
                'platform',
                'socmed',
                'address',
                'latest_education',
                'relationship',
                'guardian_name',
                'guardian_phone_number',
                'guardian_address',
                'guardian_occupation',
                'skills.*.score'
            ]));
        }
        else {
            // Get the vacancy and HRD
            $vacancy = Lowongan::find($request->vacancy);
            $hrd = HRD::find($vacancy->id_hrd);
            
            // Generate username
            $data_user = User::where('has_access','=',0)->where('username','like', $hrd->kode.'%')->latest()->first();
            if(!$data_user)
                $username = generate_username(null, $hrd->kode);
            else
                $username = generate_username($data_user->username, $hrd->kode);

            // Save the user
            $user = new User;
            $user->role_id = role('applicant');
            $user->name = $request->name;
            $user->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $user->jenis_kelamin = $request->gender;
            $user->email = $request->email;
            $user->username = $username;
            $user->password = bcrypt($username);
            $user->password_str = $username;
            $user->avatar = '';
            $user->has_access = 0;
            $user->status = 1;
            $user->last_visit = date("Y-m-d H:i:s");
            $user->save();

            // Save the applicant
            $applicant = new Pelamar;
            $applicant->id_user = $user->id;
            $applicant->id_hrd = $hrd->id_hrd;
            $applicant->nama_lengkap = $request->name;
            $applicant->tempat_lahir = $request->birthplace != '' ? $request->birthplace : '';
            $applicant->tanggal_lahir = generate_date_format($request->birthdate, 'y-m-d');
            $applicant->jenis_kelamin = $request->gender;
            $applicant->agama = $request->religion;
            $applicant->email = $request->email;
            $applicant->nomor_hp = $request->phone_number;
            $applicant->nomor_telepon = '';
            $applicant->nomor_ktp = $request->identity_number != '' ? $request->identity_number : '';
            $applicant->status_hubungan = '';
            $applicant->alamat = $request->address;
            $applicant->pendidikan_terakhir = $request->latest_education != '' ? $request->latest_education : '';
            $applicant->riwayat_pekerjaan = $request->job_experience != '' ? $request->job_experience : '';
        	$applicant->akun_sosmed = '';
        	$applicant->data_darurat = '';
            $applicant->kode_pos = '';
            $applicant->pendidikan_formal = '';
            $applicant->pendidikan_non_formal = '';
            $applicant->riwayat_pekerjaan = '';
            $applicant->keahlian = '';
            $applicant->pertanyaan = '';
            $applicant->pas_foto = '';
            $applicant->foto_ijazah = '';
            $applicant->posisi = $vacancy->id_lowongan;
            $applicant->pelamar_at = date("Y-m-d H:i:s");
            $applicant->save();

            // Redirect
            return redirect()->route('admin.applicant.index')->with(['message' => 'Berhasil menambah data.']);
        }
    }
}