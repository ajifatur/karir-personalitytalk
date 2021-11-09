<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\HRD;
use App\Models\Kantor;
use App\Models\Karyawan;
use App\Models\Pelamar;
use App\Models\Seleksi;
use App\Models\User;

class SeleksiController extends Controller
{
    /**
     * Menampilkan data seleksi
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->role == role_admin()){
            if($request->query('hrd') != null && $request->query('hasil') != null){
                $hrd = HRD::find($request->query('hrd'));

                if($hrd && ($request->query('hasil') == 1 || $request->query('hasil') == 0 || $request->query('hasil') == 99)){
                    // Get data seleksi
                    $seleksi = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$request->query('hrd'))->where('seleksi.hasil','=',$request->query('hasil'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
                elseif($hrd && ($request->query('hasil') != 1 && $request->query('hasil') != 0 && $request->query('hasil') != 99)){
                    // Get data seleksi
                    $seleksi = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$request->query('hrd'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
                elseif(!$hrd && ($request->query('hasil') == 1 || $request->query('hasil') == 0 || $request->query('hasil') == 99)){
                    // Get data seleksi
                    $seleksi = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.hasil','=',$request->query('hasil'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
                else{
                    // Get data seleksi
                    $seleksi = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
                }
            }
            else{
                // Get data seleksi
                $seleksi = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
            }
            
            // Get data kantor
            $kantor = Kantor::get();
        }
        elseif(Auth::user()->role == role_hrd()){
			// Get data HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
			
            if($request->query('hasil') != null && ($request->query('hasil') == 1 || $request->query('hasil') == 0 || $request->query('hasil') == 99)){
                // Get data seleksi
                $seleksi = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$hrd->id_hrd)->where('seleksi.hasil','=',$request->query('hasil'))->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
            }
            else{
    			// Get data seleksi
                $seleksi = Seleksi::join('pelamar','seleksi.id_pelamar','=','pelamar.id_pelamar')->join('users','pelamar.id_user','=','users.id_user')->join('lowongan','seleksi.id_lowongan','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('seleksi.id_hrd','=',$hrd->id_hrd)->orderBy('lowongan.status','desc')->orderBy('waktu_wawancara','desc')->get();
            }
            
            // Get data kantor
            $kantor = Kantor::where('id_hrd','=',$hrd->id_hrd)->get();
        }
        
        if(count($seleksi)>0){
            foreach($seleksi as $key=>$data){
                $karyawan = Karyawan::where('id_user','=',$data->id_user)->first();
                $seleksi[$key]->isKaryawan = !$karyawan ? false : true;
            }
        }

    	// View
        return view('seleksi/index', [
            'kantor' => $kantor,
            'seleksi' => $seleksi,
        ]);
    }

    /**
     * Menyimpan data pelamar yang akan diwawancara
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	// Get data HRD
    	if(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
        }

        // Validasi
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'jam' => 'required',
            'tempat' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Cek seleksi
            $cek_seleksi = Seleksi::where('id_pelamar','=',$request->id_pelamar)->where('id_lowongan','=',$request->id_lowongan)->first();

            // Jika sudah ada
            if($cek_seleksi){
                return redirect('/admin/pelamar/detail/'.$request->id_pelamar)->with(['message' => 'Sudah masuk ke data seleksi.']);
            }

            // Menambah data seleksi
            $seleksi = new Seleksi;
            $seleksi->id_hrd = isset($hrd) ? $hrd->id_hrd : 0;
            $seleksi->id_pelamar = $request->id_pelamar;
            $seleksi->id_lowongan = $request->id_lowongan;
            $seleksi->hasil = 99;
            $seleksi->waktu_wawancara = generate_date_format($request->tanggal, 'y-m-d')." ".$request->jam.":00";
            $seleksi->tempat_wawancara = $request->tempat;
            $seleksi->save();
        }

        // Redirect
        return redirect('/admin/seleksi')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Mengambil data seleksi
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        // Get data
        $seleksi = Seleksi::find($request->id);
        $seleksi->tanggal_wawancara = date('d/m/Y', strtotime($seleksi->waktu_wawancara));
        echo json_encode($seleksi);
    }

    /**
     * Mengupdate data seleksi
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'hasil' => 'required',
            'tanggal' => $request->hasil == 99 ? 'required' : '',
            'jam' => $request->hasil == 99 ? 'required' : '',
            'tempat' => $request->hasil == 99 ? 'required' : '',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Mengupdate data seleksi
            $seleksi = Seleksi::find($request->id);
            $seleksi->waktu_wawancara = $request->hasil == 99 ? generate_date_format($request->tanggal, 'y-m-d')." ".$request->jam.":00" : $seleksi->waktu_wawancara;
            $seleksi->tempat_wawancara = $request->hasil == 99 ? $request->tempat : $seleksi->tempat_wawancara;
            $seleksi->hasil = $request->hasil;
            $seleksi->save();
        }

        // Redirect
        return redirect('admin/seleksi')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Mengkonversi data pelamar ke data karyawan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function convert(Request $request)
    {
		// Get data seleksi
		$seleksi = Seleksi::find($request->id);
		
		// Get data pelamar dan user
		$pelamar = Pelamar::join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->find($seleksi->id_pelamar);
		$user = User::find($pelamar->id_user);
		
		// Menambah data karyawan
		$karyawan = new Karyawan;
		$karyawan->id_user = $pelamar->id_user;
		$karyawan->id_hrd = $pelamar->id_hrd;
		$karyawan->nama_lengkap = $pelamar->nama_lengkap;
		$karyawan->tanggal_lahir = $pelamar->tanggal_lahir;
		$karyawan->jenis_kelamin = $pelamar->jenis_kelamin;
		$karyawan->email = $pelamar->email;
		$karyawan->nomor_hp = $pelamar->nomor_hp;
		$karyawan->nik_cis = '';
		$karyawan->nik = $pelamar->nomor_ktp;
		$karyawan->alamat = $pelamar->alamat;
		$karyawan->pendidikan_terakhir = $pelamar->pendidikan_terakhir;
		$karyawan->awal_bekerja = generate_date_format($request->awal_bekerja, 'y-m-d');
		$karyawan->posisi = $pelamar->id_posisi;
		$karyawan->kantor = $request->kantor;
		$karyawan->save();
		
		// Mengubah role user
		$user->role = role_karyawan();
		$user->save();

        // Redirect
        return redirect('admin/seleksi')->with(['message' => 'Berhasil mengkonversi data pelamar ke data karyawan.']);
    }

    /**
     * Menghapus data seleksi
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $seleksi = Seleksi::find($request->id);
        $seleksi->delete();

        // Redirect
        return redirect('/admin/seleksi')->with(['message' => 'Berhasil menghapus data.']);
    }
}
