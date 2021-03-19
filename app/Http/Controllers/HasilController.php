<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PDF;
use Dompdf\FontMetrics;
use App\Hasil;
use App\HRD;
use App\Karyawan;
use App\Keterangan;
use App\Lowongan;
use App\Pelamar;
use App\Posisi;
use App\Role;
use App\Seleksi;
use App\Tes;
use App\User;

class HasilController extends Controller
{
    /**
     * Menampilkan data hasil tes
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	// Get data hasil
        if(Auth::user()->role == role_admin()){
            if(Auth::user()->username == 'ajifatur')
                $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->orderBy('hasil.test_at','desc')->get();
            else{				
                $ids = array();
                $umum = User::where('role','=',5)->get();
                foreach($umum as $data){
                    array_push($ids, $data->id_user);
                }
                
				if($request->query('hrd') != null){
					$hrd = HRD::find($request->query('hrd'));
                	$hasil = $hrd ? Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('hasil.id_hrd','=',$request->query('hrd'))->whereNotIn('hasil.id_user',$ids)->orderBy('hasil.test_at','desc')->get() : Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('id_hrd','=',$request->query('hrd'))->whereNotIn('hasil.id_user',$ids)->orderBy('hasil.test_at','desc')->get();
				}
				else{
					$hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->whereNotIn('hasil.id_user',$ids)->orderBy('hasil.test_at','desc')->get();
				}
            }
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('hasil.id_hrd','=',$hrd->id_hrd)->whereIn('users.role',[2,3,4])->orderBy('hasil.test_at','desc')->get();
        }
        
		// Get data user
		if(count($hasil)>0){
			foreach($hasil as $key=>$data){
				$pelamar = $data->role == role_pelamar() ? Pelamar::where('id_user','=',$data->id_user)->first() : null;
				$karyawan = $data->role == role_karyawan() ? Karyawan::where('id_user','=',$data->id_user)->first() : null;
				if($karyawan != null){
					$data->id_user = User::find($data->id_user);
					$hasil[$key]->posisi = Posisi::find($karyawan->posisi);
					$hasil[$key]->role = Role::find($data->id_user->role);
				}
				elseif($pelamar != null){
					$data->id_user = User::find($data->id_user);
					$hasil[$key]->pelamar = Pelamar::where('id_user','=',$data->id_user)->first();
					$hasil[$key]->posisi = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->find($pelamar->posisi);
					$hasil[$key]->role = Role::find($data->id_user->role);
				}
				else{
					$data->id_user = User::find($data->id_user);
					$hasil[$key]->posisi = null;
					$hasil[$key]->role = Role::find($data->id_user->role);
				}
			}
		}

        // View		
        return view('hasil/index', [
            'hasil' => $hasil,
        ]);
    }

    /**
     * Menampilkan data hasil tes karyawan
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function employeer(Request $request)
    {
        // Get data hasil
        if(Auth::user()->role == role_admin()){            
            if($request->query('hrd') != null && $request->query('tes') != null){
                $hrd = HRD::find($request->query('hrd'));
                $tes = Tes::find($request->query('tes'));

                if($hrd && $tes){
                    // Data hasil
                    $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_karyawan())->where('hasil.id_hrd','=',$request->query('hrd'))->where('hasil.id_tes','=',$request->query('tes'))->orderBy('hasil.test_at','desc')->get();
                }
                elseif($hrd && !$tes){
                    // Data hasil
                    $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_karyawan())->where('hasil.id_hrd','=',$request->query('hrd'))->orderBy('hasil.test_at','desc')->get();
                }
                elseif($hrd && !$tes){
                    // Data hasil
                    $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_karyawan())->where('hasil.id_tes','=',$request->query('tes'))->orderBy('hasil.test_at','desc')->get();
                }
                else{
                    // Data hasil
                    $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_karyawan())->orderBy('hasil.test_at','desc')->get();
                }
            }
            else{
                // Data hasil
                $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_karyawan())->orderBy('hasil.test_at','desc')->get();
            }
        }
        elseif(Auth::user()->role == role_hrd()){
            // Data HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();

            if($request->query('tes') != null){
                $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_karyawan())->where('hasil.id_hrd','=',$hrd->id_hrd)->where('hasil.id_tes','=',$request->query('tes'))->orderBy('hasil.test_at','desc')->get();
            }
            else{
                $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_karyawan())->where('hasil.id_hrd','=',$hrd->id_hrd)->orderBy('hasil.test_at','desc')->get();
            }
        }
        
        // Get data user
        if(count($hasil)>0){
            foreach($hasil as $key=>$data){
                $karyawan = $data->role == role_karyawan() ? Karyawan::where('id_user','=',$data->id_user)->first() : null;
                if($karyawan != null){
                    $data->id_user = User::find($data->id_user);
                    $hasil[$key]->posisi = Posisi::find($karyawan->posisi);
                    $hasil[$key]->role = Role::find($data->id_user->role);
                }
            }
        }

        // View     
        return view('hasil/employeer', [
            'hasil' => $hasil,
        ]);
    }

    /**
     * Menampilkan data hasil tes pelamar
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function applicant(Request $request)
    {
        // Get data hasil
        if(Auth::user()->role == role_admin()){            
            if($request->query('hrd') != null && $request->query('tes') != null){
                $hrd = HRD::find($request->query('hrd'));
                $tes = Tes::find($request->query('tes'));

                if($hrd && $tes){
                    // Data hasil
                    $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_pelamar())->where('hasil.id_hrd','=',$request->query('hrd'))->where('hasil.id_tes','=',$request->query('tes'))->orderBy('hasil.test_at','desc')->get();
                }
                elseif($hrd && !$tes){
                    // Data hasil
                    $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_pelamar())->where('hasil.id_hrd','=',$request->query('hrd'))->orderBy('hasil.test_at','desc')->get();
                }
                elseif($hrd && !$tes){
                    // Data hasil
                    $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_pelamar())->where('hasil.id_tes','=',$request->query('tes'))->orderBy('hasil.test_at','desc')->get();
                }
                else{
                    // Data hasil
                    $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_pelamar())->orderBy('hasil.test_at','desc')->get();
                }
            }
            else{
                // Data hasil
                $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_pelamar())->orderBy('hasil.test_at','desc')->get();
            }
        }
        elseif(Auth::user()->role == role_hrd()){
            // Data HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();

            if($request->query('tes') != null){
                $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_pelamar())->where('hasil.id_hrd','=',$hrd->id_hrd)->where('hasil.id_tes','=',$request->query('tes'))->orderBy('hasil.test_at','desc')->get();
            }
            else{
                $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_pelamar())->where('hasil.id_hrd','=',$hrd->id_hrd)->orderBy('hasil.test_at','desc')->get();
            }
        }
        
        // Get data user
        if(count($hasil)>0){
            foreach($hasil as $key=>$data){
                $pelamar = $data->role == role_pelamar() ? Pelamar::where('id_user','=',$data->id_user)->first() : null;
                if($pelamar != null){
                    $data->id_user = User::find($data->id_user);
                    $hasil[$key]->pelamar = Pelamar::where('id_user','=',$data->id_user)->first();
                    $hasil[$key]->posisi = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('lowongan.id_lowongan','=',$pelamar->posisi)->first();
                    $hasil[$key]->role = Role::find($data->id_user->role);
                }
            }
        }

        // View     
        return view('hasil/applicant', [
            'hasil' => $hasil,
        ]);
    }

    /**
     * Menampilkan data hasil tes magang
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function internship(Request $request)
    {
        // Get data hasil
        if(Auth::user()->role == role_admin()){           
            if($request->query('tes') != null){
                // Data hasil
                $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_magang())->where('hasil.id_tes','=',$request->query('tes'))->orderBy('hasil.test_at','desc')->get();
            }
            else{
                // Data hasil
                $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role_magang())->orderBy('hasil.test_at','desc')->get();
            }
        }
        else{
            // View
            return view('error/404');
        }
        
        // Get data user
        if(count($hasil)>0){
            foreach($hasil as $key=>$data){
                $data->id_user = User::find($data->id_user);
                $hasil[$key]->posisi = $data->jenis_kelamin;
                $hasil[$key]->role = Role::find($data->id_user->role);
            }
        }

        // View     
        return view('hasil/internship', [
            'hasil' => $hasil,
        ]);
    }

    /**
     * Menampilkan hasil tes
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
    	// Get data HRD dan hasil
    	if(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->where('id_hasil','=',$id)->where('hasil.id_hrd','=',$hrd->id_hrd)->first();
        }
        else{
            $hasil = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->find($id);
        }

        // Jika tidak ada data hasil
        if(!$hasil){
            abort(404);
        }
        else{
            $tes = Tes::find($hasil->id_tes);
            if($hasil->path != 'disc-40-soal' && $hasil->path != 'disc-24-soal' && $hasil->path != 'papikostick' && $hasil->path != 'sdi' && $hasil->path != 'msdt') abort(404);
            $hasil->hasil = json_decode($hasil->hasil, true);

        	// User
        	$user = User::find($hasil->id_user);
        	$role = Role::find($user->role);
			if($user->role == role_karyawan()) $user_desc = Karyawan::join('posisi','karyawan.posisi','=','posisi.id_posisi')->where('id_user','=',$hasil->id_user)->first();
        	elseif($user->role == role_pelamar()) $user_desc = Pelamar::join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_user','=',$hasil->id_user)->first();
        	else $user_desc = array();
        }

        // Jika tes DISC 40 soal
        if($hasil->path == 'disc-40-soal'){
            return $this->detail_disc($hasil, $tes, $user, $user_desc, $role);
        }
        // Jika tes DISC 24 soal
        elseif($hasil->path == 'disc-24-soal'){
            return $this->detail_disc_24($hasil, $tes, $user, $user_desc, $role);
        }
        // Jika tes Papikostick
        elseif($hasil->path == 'papikostick'){
            return $this->detail_papikostick($hasil, $tes, $user, $user_desc, $role);
        }
        // Jika tes SDI
        elseif($hasil->path == 'sdi'){
            return $this->detail_sdi($hasil, $tes, $user, $user_desc, $role);
        }
        // Jika tes MSDT
        elseif($hasil->path == 'msdt'){
            return $this->detail_msdt($hasil, $tes, $user, $user_desc, $role);
        }
    }

    /**
     * Menampilkan hasil tes DISC 40 Soal
     *
     * object $hasil
     * object $tes
     * object $user
     * object $pelamar
     * object $role
     * @return \Illuminate\Http\Response
     */
    public function detail_disc($hasil, $tes, $user, $user_desc, $role)
    {
        // DISC
        $disc = array('D', 'I', 'S','C');
        $m_score = $hasil->hasil['M'];
        $l_score = $hasil->hasil['L'];

        // Ranking
        $disc_score_m = sortScore($m_score);
        $disc_score_l = sortScore($l_score);

        // Kode
        $code_m = setCode($disc_score_m);
        $code_l = setCode($disc_score_l);

        // Keterangan
        $keterangan = Keterangan::where('id_paket','=',$hasil->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
        $kode_keterangan = substr($code_l[0],1,1);
        switch($kode_keterangan){
            case 'D':
                $hasil_keterangan = $keterangan->keterangan[searchIndex($keterangan->keterangan, "disc", "D")]["keterangan"];
            break;
            case 'I':
                $hasil_keterangan = $keterangan->keterangan[searchIndex($keterangan->keterangan, "disc", "I")]["keterangan"];
            break;
            case 'S':
                $hasil_keterangan = $keterangan->keterangan[searchIndex($keterangan->keterangan, "disc", "S")]["keterangan"];
            break;
            case 'C':
                $hasil_keterangan = $keterangan->keterangan[searchIndex($keterangan->keterangan, "disc", "C")]["keterangan"];
            break;
        }

        // View
        return view('hasil/'.$tes->path.'/detail', [
            'hasil' => $hasil,
            'role' => $role,
            'user' => $user,
            'user_desc' => $user_desc,
            'disc' => $disc,
            'disc_score_m' => $disc_score_m,
            'disc_score_l' => $disc_score_l,
            'code_m' => $code_m,
            'code_l' => $code_l,
            'kode_keterangan' => $kode_keterangan,
            'hasil_keterangan' => $hasil_keterangan,
        ]);
    }

    /**
     * Menampilkan hasil tes DISC 24 Soal
     *
     * object $hasil
     * object $tes
     * object $user
     * object $pelamar
     * object $role
     * @return \Illuminate\Http\Response
     */
    public function detail_disc_24($hasil, $tes, $user, $user_desc, $role)
    {
        // Array selisih
        $array_selisih = [
            'D' => $hasil->hasil['dm'] - $hasil->hasil['dl'],
            'I' => $hasil->hasil['im'] - $hasil->hasil['il'],
            'S' => $hasil->hasil['sm'] - $hasil->hasil['sl'],
            'C' => $hasil->hasil['cm'] - $hasil->hasil['cl'],
        ];

        // Array 1
        $array_1 = [
            0   => [-6, -7, -5.7, -6],
            1   => [-5.3, -4.6, -4.3, -4.7],
            2   => [-4, -2.5, -3.5, -3.5],
            3   => [-2.5, -1.3, -1.5, -1.5],
            4   => [-1.7, 1, -0.7, 0.5],
            5   => [-1.3, 3, 0.5, 2],
            6   => [0, 3.5, 1, 3],
            7   => [0.5, 5.3, 2.5, 5.3],
            8   => [1, 5.7, 3, 5.7],
            9   => [2, 6, 4, 6],
            10  => [3, 6.5, 4.6, 6.3],
            11  => [3.5, 7, 5, 6.5],
            12  => [4, 7, 5.7, 6.7],
            13  => [4.7, 7, 6, 7],
            14  => [5.3, 7, 6.5, 7.3],
            15  => [6.5, 7, 6.5, 7.3],
            16  => [7, 7.5, 7, 7.3],
            17  => [7, 7.5, 7, 7.5],
            18  => [7, 7.5, 7, 8],
            19  => [7.5, 7.5, 7.5, 8],
            20  => [7.5, 8, 7.5, 8],
        ];

        // Array 2
        $array_2 = [
            0   => [7.5, 7, 7.5, 7.5],
            1   => [6.5, 6, 7, 7],
            2   => [4.3, 4, 6, 5.6],
            3   => [2.5, 2.5, 4, 4],
            4   => [1.5, 0.5, 2.5, 2.5],
            5   => [0.5, 0, 1.5, 1.5],
            6   => [0, -2, 0.5, 0.5],
            7   => [-1.3, -3.5, -1.3, 0],
            8   => [-1.5, -4.3, -2, -1.3],
            9   => [-2.5, -5.3, -3, -2.5],
            10  => [-3, -6, -4.3, -3.5],
            11  => [-3.5, -6.5, -5.3, -5.3],
            12  => [-4.3, -7, -6, -5.7],
            13  => [-5.3, -7.2, -6.5, -6],
            14  => [-5.7, -7.2, -6.7, -6.5],
            15  => [-6, -7.2, -6.7, -7],
            16  => [-6.5, -7.3, -7, -7.3],
            17  => [6.7, -7.3, -7.2, -7.5],
            18  => [7, -7.3, -7.3, -7.7],
            19  => [-7.3, -7.5, -7.5, -7.9],
            20  => [-7.5, -8, -8, -8],
        ];

        // Array 3
        $array_3 = [
            -22 => [-8, -8, -8, -7.5],
            -21 => [-7.5, -8, -8, -7.3],
            -20 => [-7, -8, -8, -7.3],
            -19 => [-6.8, -8, -8, -7],
            -18 => [-6.75, -7, -7.5, -6.7],
            -17 => [-6.7, -6.7, -7.3, -6.7],
            -16 => [-6.5, -6.7, -7.3, -6.7],
            -15 => [-6.3, -6.7, -7, -6.5],
            -14 => [-6.1, -6.7, -6.5, -6.3],
            -13 => [-5.9, -6.7, -6.5, -6],
            -12 => [-5.7, -6.7, -6.5, -5.85],
            -11 => [-5.3, -6.7, -6.5, -5.85],
            -10 => [-4.3, -6.5, -6, -5.7],
            -9  => [-3.5, -6, -4.7, -4.7],
            -8  => [-3.25, -5.7, -4.3, -4.3],
            -7  => [-3, -4.7, -3.5, -3.5],
            -6  => [-2.75, -4.3, -3, -3],
            -5  => [-2.5, -3.5, -2, -2.5],
            -4  => [-1.5, -3, -1.5, -0.5],
            -3  => [-1, -2, -1, 0],
            -2  => [-0.5, -1.5, -0.5, 0.3],
            -1  => [-0.25, 0, 0, 0.5],
            0   => [0, 0.5, 1, 1.5],
            1   => [0.5, 1, 1.5, 3],
            2   => [0.7, 1.5, 2, 4],
            3   => [1, 3, 3, 4.3],
            4   => [1.3, 4, 3.5, 5.5],
            5   => [1.5, 4.3, 4, 5.7],
            6   => [2, 5, 0, 6], // S is empty
            7   => [2.5, 5.5, 4.7, 6.3],
            8   => [3.5, 6.5, 5, 6.5],
            9   => [4, 6.7, 5.5, 6.7],
            10  => [4.7, 7, 6, 7],
            11  => [4.85, 7.3, 6.2, 7.3],
            12  => [5, 7.3, 6.3, 7.3],
            13  => [5.5, 7.3, 6.5, 7.3],
            14  => [6, 7.3, 6.7, 7.3],
            15  => [6.3, 7.3, 7, 7.3],
            16  => [6.5, 7.3, 7.3, 7.3],
            17  => [6.7, 7.3, 7.3, 7.5],
            18  => [7, 7.5, 7.3, 8],
            19  => [7.3, 8, 7.3, 8],
            20  => [7.3, 8, 7.5, 8],
            21  => [7.5, 8, 8, 8],
            22  => [8, 8, 8, 8],
        ];

        // Graph
        $graph = [
            1 => [
                'D' => $array_1[$hasil->hasil['dm']][0],
                'I' => $array_1[$hasil->hasil['im']][1],
                'S' => $array_1[$hasil->hasil['sm']][2],
                'C' => $array_1[$hasil->hasil['cm']][3],
            ],
            2 => [
                'D' => $array_2[$hasil->hasil['dl']][0],
                'I' => $array_2[$hasil->hasil['il']][1],
                'S' => $array_2[$hasil->hasil['sl']][2],
                'C' => $array_2[$hasil->hasil['cl']][3],
            ],
            3 => [
                'D' => $array_3[$array_selisih['D']][0],
                'I' => $array_3[$array_selisih['I']][1],
                'S' => $array_3[$array_selisih['S']][2],
                'C' => $array_3[$array_selisih['C']][3],
            ],
        ];

        // Set kepribadian
        $array_kepribadian = [
            'most' => [],
            'least' => [],
            'change' => [],
        ];
        for($i = 0; $i < 40; $i++){
            array_push($array_kepribadian['most'], analisis_disc_24($i + 1, $graph[1]['D'], $graph[1]['I'], $graph[1]['S'], $graph[1]['C']));
            array_push($array_kepribadian['least'], analisis_disc_24($i + 1, $graph[2]['D'], $graph[2]['I'], $graph[2]['S'], $graph[2]['C']));
            array_push($array_kepribadian['change'], analisis_disc_24($i + 1, $graph[3]['D'], $graph[3]['I'], $graph[3]['S'], $graph[3]['C']));
        }

        // Index
        $index = [
            'most' => [],
            'least' => [],
            'change' => [],
        ];
        foreach($array_kepribadian['most'] as $key=>$value){
            if($value == 1) array_push($index['most'], $key);
        }
        foreach($array_kepribadian['least'] as $key=>$value){
            if($value == 1) array_push($index['least'], $key);
        }
        foreach($array_kepribadian['change'] as $key=>$value){
            if($value == 1) array_push($index['change'], $key);
        }

        // Keterangan
        $keterangan = Keterangan::where('id_paket','=',$hasil->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);

        // View
        return view('hasil/'.$tes->path.'/detail', [
            'hasil' => $hasil,
            'user_desc' => $user_desc,
            'role' => $role,
            'user' => $user,
            'array_selisih' => $array_selisih,
            'graph' => $graph,
            'index' => $index,
            'keterangan' => $keterangan,
        ]);
    }

    /**
     * Menampilkan hasil tes Papikostick
     *
     * object $hasil
     * object $tes
     * object $user
     * object $pelamar
     * object $role
     * @return \Illuminate\Http\Response
     */
    public function detail_papikostick($hasil, $tes, $user, $user_desc, $role)
    {
        // Keterangan
        $keterangan = Keterangan::where('id_paket','=',$hasil->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
        
        // Array huruf
        $huruf = ["N","G","A","L","P","I","T","V","X","S","B","O","R","D","C","Z","E","K","F","W"];
        
        // View
        return view('hasil/'.$tes->path.'/detail', [
            'hasil' => $hasil,
            'huruf' => $huruf,
            'keterangan' => $keterangan,
            'user_desc' => $user_desc,
            'role' => $role,
            'user' => $user,
        ]);
    }

    /**
     * Menampilkan hasil tes SDI
     *
     * object $hasil
     * object $tes
     * object $user
     * object $pelamar
     * object $role
     * @return \Illuminate\Http\Response
     */
    public function detail_sdi($hasil, $tes, $user, $user_desc, $role)
    {
        // Keterangan
        // $keterangan = Keterangan::where('id_paket','=',$hasil->id_paket)->first();
        // $keterangan->keterangan = json_decode($keterangan->keterangan, true);
        
        // View
        return view('hasil/'.$tes->path.'/detail', [
            'hasil' => $hasil,
            // 'keterangan' => $keterangan,
            'user_desc' => $user_desc,
            'role' => $role,
            'user' => $user,
        ]);
    }

    /**
     * Menampilkan hasil tes MSDT
     *
     * object $hasil
     * object $tes
     * object $user
     * object $pelamar
     * object $role
     * @return \Illuminate\Http\Response
     */
    public function detail_msdt($hasil, $tes, $user, $user_desc, $role)
    {
        // Keterangan
        $keterangan = Keterangan::where('id_paket','=',$hasil->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
        
        // View
        return view('hasil/'.$tes->path.'/detail', [
            'hasil' => $hasil,
            'keterangan' => $keterangan,
            'user_desc' => $user_desc,
            'role' => $role,
            'user' => $user,
        ]);
    }

    /**
     * PDF
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request)
    {
        ini_set('max_execution_time', '300');

        // Jika tes DISC 40 soal
        if($request->path == 'disc-40-soal'){
            return $this->pdf_disc_40_soal($request);
        }
        // Jika tes DISC 24 soal
        elseif($request->path == 'disc-24-soal'){
            return $this->pdf_disc_24_soal($request);
        }
        // Jika tes Papikostick
        elseif($request->path == 'papikostick'){
            return $this->pdf_papikostick($request);
        }
        // Jika tes SDI
        elseif($request->path == 'sdi'){
            return $this->pdf_sdi($request);
        }
        // Jika tes MSDT
        elseif($request->path == 'msdt'){
            return $this->pdf_msdt($request);
        }
    }

    /**
     * PDF DISC 40 Soal
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf_disc_40_soal(Request $request)
    {
        // DISC
        $disc = array('D', 'I', 'S','C');

        // Keterangan
        $keterangan = Keterangan::where('id_paket','=',$request->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
        $kode_keterangan = $request->kode_keterangan;
        switch($kode_keterangan){
            case 'D':
                $deskripsi = $keterangan->keterangan[searchIndex($keterangan->keterangan, "disc", "D")]["keterangan"];
            break;
            case 'I':
                $deskripsi = $keterangan->keterangan[searchIndex($keterangan->keterangan, "disc", "I")]["keterangan"];
            break;
            case 'S':
                $deskripsi = $keterangan->keterangan[searchIndex($keterangan->keterangan, "disc", "S")]["keterangan"];
            break;
            case 'C':
                $deskripsi = $keterangan->keterangan[searchIndex($keterangan->keterangan, "disc", "C")]["keterangan"];
            break;
        }
        
        // PDF
        $pdf = PDF::loadview('hasil/'.$request->path.'/pdf', [
            'mostChartImage' => $request->mostChartImage,
            'leastChartImage' => $request->leastChartImage,
            'deskripsi' => $deskripsi,
            'nama' => $request->nama,
            'usia' => $request->usia,
            'jenis_kelamin' => $request->jenis_kelamin,
            'posisi' => $request->posisi,
            'tes' => $request->tes,
            'disc_score_m' => json_decode($request->disc_score_m, true),
            'disc_score_l' => json_decode($request->disc_score_l, true),
            'most' => $request->most,
            'least' => $request->least,
            'disc' => $disc,
        ]);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream("result.pdf");
    }

    /**
     * PDF DISC 24 Soal
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf_disc_24_soal(Request $request)
    {
        // DISC
        $disc = array('D', 'I', 'S','C');
		
		// Index
		$index = json_decode($request->index, true);

        // Keterangan
        $keterangan = Keterangan::where('id_paket','=',$request->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
		
		// Keterangan MOST, LEAST, CHANGE
		$most = $keterangan->keterangan[$index['most'][0]];
		$least = $keterangan->keterangan[$index['least'][0]];
		$change = $keterangan->keterangan[$index['change'][0]];
		
		// Job match
        
        // PDF
        $pdf = PDF::loadview('hasil/'.$request->path.'/pdf', [
            'mostChartImage' => $request->mostChartImage,
            'leastChartImage' => $request->leastChartImage,
            'changeChartImage' => $request->changeChartImage,
            'nama' => $request->nama,
            'usia' => $request->usia,
            'jenis_kelamin' => $request->jenis_kelamin,
            'posisi' => $request->posisi,
            'tes' => $request->tes,
            'hasil' => $request->hasil,
            'array_selisih' => $request->array_selisih,
            'index' => $request->index,
            'disc' => $disc,
            'most' => $most,
            'least' => $least,
            'change' => $change,
        ]);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream("result.pdf");
    }

    /**
     * PDF Papikostick
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf_papikostick(Request $request)
    {
        // Hasil
        $hasil = Hasil::find($request->id_hasil);
        $hasil->hasil = json_decode($hasil->hasil, true);
        
        // Keterangan
        $keterangan = Keterangan::where('id_paket','=',$hasil->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
        
        // Array huruf
        $huruf = ["N","G","A","L","P","I","T","V","X","S","B","O","R","D","C","Z","E","K","F","W"];
        
        // PDF
        $pdf = PDF::loadview('hasil/'.$request->path.'/pdf', [
            'hasil' => $hasil,
            'huruf' => $huruf,
            'keterangan' => $keterangan,
            'image' => $request->image,
            'nama' => $request->nama,
            'usia' => $request->usia,
            'jenis_kelamin' => $request->jenis_kelamin,
            'posisi' => $request->posisi,
            'tes' => $request->tes,
        ]);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream("result.pdf");
    }

    /**
     * PDF SDI
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf_sdi(Request $request)
    {
        // Hasil
        $hasil = Hasil::find($request->id_hasil);
        $hasil->hasil = json_decode($hasil->hasil, true);
        
        // PDF
        $pdf = PDF::loadview('hasil/'.$request->path.'/pdf', [
            'hasil' => $hasil,
            'image' => $request->image,
            'nama' => $request->nama,
            'usia' => $request->usia,
            'jenis_kelamin' => $request->jenis_kelamin,
            'posisi' => $request->posisi,
            'tes' => $request->tes,
        ]);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream("result.pdf");
    }

    /**
     * PDF MSDT
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf_msdt(Request $request)
    {
        // Hasil
        $hasil = Hasil::find($request->id_hasil);
        $hasil->hasil = json_decode($hasil->hasil, true);
        
        // Keterangan
        $keterangan = Keterangan::where('id_paket','=',$hasil->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
        
        // PDF
        $pdf = PDF::loadview('hasil/'.$request->path.'/pdf', [
            'hasil' => $hasil,
            'image' => $request->image,
            'nama' => $request->nama,
            'usia' => $request->usia,
            'jenis_kelamin' => $request->jenis_kelamin,
            'posisi' => $request->posisi,
            'tes' => $request->tes,
            'keterangan' => $keterangan,
        ]);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream("result.pdf");
    }

    /**
     * Menghapus hasil
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $hasil = Hasil::find($request->id);
        $hasil->delete();

        // Redirect
        return redirect('admin/hasil')->with(['message' => 'Berhasil menghapus data.']);
    }
}
