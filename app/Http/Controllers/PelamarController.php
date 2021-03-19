<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PelamarExport;
use App\Agama;
use App\HRD;
use App\Karyawan;
use App\Lowongan;
use App\Pelamar;
use App\Seleksi;
use App\User;

class PelamarController extends Controller
{
    /**
     * Menampilkan data pelamar
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	// Get data pelamar
        if(Auth::user()->role == role_admin()){
			if($request->query('hrd') != null){
            	$hrd = HRD::find($request->query('hrd'));
    	    	$pelamar = $hrd ? Pelamar::where('id_hrd','=',$request->query('hrd'))->orderBy('created_at','desc')->get() : Pelamar::orderBy('created_at','desc')->get();
			}
			else{
    	    	$pelamar = Pelamar::orderBy('created_at','desc')->get();
			}
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
    	    $pelamar = Pelamar::where('id_hrd','=',$hrd->id_hrd)->orderBy('created_at','desc')->get();
        }
        
    	// Setting data pelamar
        foreach($pelamar as $key=>$data){
            $data->id_user = User::find($data->id_user);
            $data->id_hrd = HRD::find($data->id_hrd);
			$lowongan = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->find($data->posisi);
            $lowongan != null ? $data->posisi = $lowongan : $pelamar->forget($key);
        }

    	// View
    	return view('pelamar/index', [
    		'pelamar' => $pelamar,
    	]);
    }

    /**
     * Menampilkan detail pelamar
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
    	// Get data pelamar
        if(Auth::user()->role == role_admin()){
    	    $pelamar = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->first();
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
    	    $pelamar = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->where('id_hrd','=',$hrd->id_hrd)->first();
        }

    	// Jika tidak ada data
    	if(!$pelamar){
    		abort(404);
    	}
        // Jika ada data
        else{
            // Data lowongan
            $pelamar->posisi = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_lowongan','=',$pelamar->posisi)->first();
			
			// Data user
			$pelamar->user = User::find($pelamar->id_user);

            // Data seleksi
            $seleksi = Seleksi::where('id_pelamar','=',$id)->where('id_lowongan','=',$pelamar->posisi->id_lowongan)->first();

            // Set data
            $pelamar->akun_sosmed = json_decode($pelamar->akun_sosmed, true);
            $pelamar->data_darurat = json_decode($pelamar->data_darurat, true);
            $pelamar->keahlian = json_decode($pelamar->keahlian, true);

            // View
            return view('pelamar/detail', [
                'pelamar' => $pelamar,
                'seleksi' => $seleksi,
            ]);
        }
    }

    /**
     * Menampilkan form edit pelamar
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	// Get data pelamar
        if(Auth::user()->role == role_admin()){
    	    $pelamar = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->first();
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
    	    $pelamar = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->where('id_hrd','=',$hrd->id_hrd)->first();
        }

    	// Jika tidak ada data pelamar
    	if(!$pelamar){
    		abort(404);
    	}
        // Jika ada data pelamar
        else{
            $pelamar->posisi = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_lowongan','=',$pelamar->posisi)->first();
        	$pelamar->akun_sosmed = json_decode($pelamar->akun_sosmed, true);
        	$pelamar->data_darurat = json_decode($pelamar->data_darurat, true);
        	$pelamar->keahlian = json_decode($pelamar->keahlian, true);
        }

        // Data agama
        $agama = Agama::all();

        // View
        return view('pelamar/edit', [
        	'pelamar' => $pelamar,
        	'agama' => $agama,
        ]);
    }

    /**
     * Mengupdate data pelamar
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|min:3|max:255',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'email' => 'required|email',
            'nomor_hp' => 'required|numeric',
            'alamat' => 'required',
            'pendidikan_terakhir' => 'required',
            'nama_orang_tua' => 'required',
            'nomor_hp_orang_tua' => 'required|numeric',
            'alamat_orang_tua' => 'required',
            'pekerjaan_orang_tua' => 'required',
            'keahlian.*.skor' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
        	// Array akun sosmed
        	$akun_sosmed = array();
        	foreach($request->get('akun_sosmed') as $key=>$value){
        		$akun_sosmed[$key] = $value != null ? $value : '';
        	}
        	
        	// Array data darurat
        	$data_darurat = array();
        	$data_darurat['nama_orang_tua'] = $request->nama_orang_tua;
        	$data_darurat['nomor_hp_orang_tua'] = $request->nomor_hp_orang_tua;
        	$data_darurat['alamat_orang_tua'] = $request->alamat_orang_tua;
        	$data_darurat['pekerjaan_orang_tua'] = $request->pekerjaan_orang_tua;
        	
            // Mengupdate data
            $pelamar = Pelamar::find($request->id);
            $pelamar->nama_lengkap = $request->nama_lengkap;
            $pelamar->tempat_lahir = $request->tempat_lahir;
            $pelamar->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $pelamar->jenis_kelamin = $request->jenis_kelamin;
            $pelamar->agama = $request->agama;
            $pelamar->email = $request->email;
            $pelamar->nomor_hp = $request->nomor_hp;
            $pelamar->nomor_telepon = $request->nomor_telepon != null ? $request->nomor_telepon : '';
            $pelamar->nomor_ktp = $request->nomor_ktp != null ? $request->nomor_ktp : '';
            $pelamar->alamat = $request->alamat;
            $pelamar->pendidikan_terakhir = $request->pendidikan_terakhir;
        	$pelamar->akun_sosmed = json_encode($akun_sosmed);
        	$pelamar->data_darurat = json_encode($data_darurat);
            $pelamar->save();

            // Mengupdate data user
            $user = User::find($request->id_user);
            $user->nama_user = $request->nama_lengkap;
            $user->tanggal_lahir = generate_date_format($request->tanggal_lahir, 'y-m-d');
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->email = $request->email;
            $user->save();
        }

        // Redirect
        return redirect('admin/pelamar/detail/'.$request->id)->with(['message' => 'Berhasil memperbarui data.']);
    }

    /**
     * Menghapus data pelamar...
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {        
        if($karyawan != null){
            $karyawan->delete();
        }
        
        if($seleksi != null){
            $seleksi->delete();
        }

        if($pelamar->delete() && $user->delete()){
            echo "Berhasil menghapus data!";
        }

        // Menghapus data
        $pelamar = Pelamar::find($request->id);
        $pelamar->delete();
        $user = User::find($pelamar->id_user);
        $user->delete();
        $karyawan = Karyawan::where('id_user','=',$pelamar->id_user)->first();
        if($pelamar) $karyawan->delete();
        $seleksi = Seleksi::where('id_pelamar','=',$request->id)->first();
        if($seleksi) $seleksi->delete();

        // Redirect
        return redirect('admin/pelamar')->with(['message' => 'Berhasil menghapus data.']);
    }
    
    /**
     * Export ke Excel
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        ini_set("memory_limit", "-1");
        
        if(Auth::user()->role == role_admin()){
            // Cek HRD
            $hrd = HRD::find($request->query('hrd'));

            // Get data pelamar
            if($hrd)
                $pelamar = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_hrd','=',$hrd->id_hrd)->get();
            else
                $pelamar = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->get();

            // Nama file
            $nama_file = $hrd ? 'Data Pelamar '.$hrd->perusahaan.' ('.date('Y-m-d-H-i-s').')' : 'Data Semua Pelamar ('.date('d-m-Y-H-i-s').')';

            return Excel::download(new PelamarExport($pelamar), $nama_file.'.xlsx');
        }
        elseif(Auth::user()->role == role_hrd()){
            // Cek HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();

            // Data pelamar
            $pelamar = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_hrd','=',$hrd->id_hrd)->get();

            // Nama file
            $nama_file = 'Data Pelamar '.$hrd->perusahaan.' ('.date('Y-m-d-H-i-s').')';

            return Excel::download(new PelamarExport($pelamar), $nama_file.'.xlsx');
        }
    }
}
