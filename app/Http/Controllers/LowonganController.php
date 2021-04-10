<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\HRD;
use App\Lowongan;
use App\Pelamar;
use App\Posisi;
use App\Seleksi;

class LowonganController extends Controller
{
    /**
     * Menampilkan data lowongan
     * 
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	// Get data lowongan
        if(Auth::user()->role == role_admin()){
			if($request->query('hrd') != null){
            	$hrd = HRD::find($request->query('hrd'));
    	    	$lowongan = $hrd ? Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('hrd.id_hrd','=',$request->query('hrd'))->orderBy('status','desc')->orderBy('created_at','desc')->get() : Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('status','desc')->orderBy('created_at','desc')->get();
			}
			else{
				$lowongan = Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('status','desc')->orderBy('created_at','desc')->get();
			}
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
    	    $lowongan = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('lowongan.id_hrd','=',$hrd->id_hrd)->orderBy('status','desc')->orderBy('created_at','desc')->get();
        }
        
    	// Setting data lowongan
        foreach($lowongan as $key=>$data){
            $pelamar = Pelamar::where('posisi','=',$data->id_lowongan)->count();
            $lowongan[$key]->pelamar = $pelamar;
        }

    	// View
    	return view('lowongan/index', [
    		'lowongan' => $lowongan,
    	]);
    }

    /**
     * Menampilkan form input lowongan
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	// Get data HRD
    	$hrd = HRD::all();
    	
    	// Get data jabatan
        if(Auth::user()->role == role_admin()){
            $posisi = Posisi::orderBy('nama_posisi','asc')->get();
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $posisi = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
        }
        
    	// View
        return view('lowongan/create', [
            'hrd' => $hrd,
            'posisi' => $posisi
        ]);
    }

    /**
     * Menyimpan lowongan
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
            'judul' => 'required|max:255',
            'jabatan' => 'required|max:255',
            // 'deskripsi_lowongan' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Get posisi
            $posisi = Posisi::find($request->jabatan);

            // Menyimpan 
            $lowongan = new Lowongan;
            $lowongan->id_hrd = $posisi ? $posisi->id_hrd : 0;
            $lowongan->judul_lowongan = $request->judul;
            // $lowongan->deskripsi_lowongan = htmlentities($request->deskripsi_lowongan);
            $lowongan->deskripsi_lowongan = '';
            $lowongan->posisi = $request->jabatan;
            $lowongan->url_lowongan = '';
            $lowongan->status = $request->status;
            $lowongan->created_at = date("Y-m-d H:i:s");
            $lowongan->save();

            // Generate url lowongan
            $data = Lowongan::where('created_at','=',$lowongan->created_at)->first();
            $data->url_lowongan = md5($data->id_lowongan);
            $data->save();
        }

        // Redirect
        return redirect('/admin/lowongan')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Menampilkan form edit lowongan
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	// Get data HRD dan lowongan
    	if(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $lowongan = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_lowongan','=',$id)->where('lowongan.id_hrd','=',$hrd->id_hrd)->first();
        }
        else{
            $lowongan = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->find($id);
        }

    	// Jika tidak ada data
    	if(!$lowongan){
    		abort(404);
    	}
    	
    	// Get data jabatan
        if(Auth::user()->role == role_admin()){
            $posisi = Posisi::where('id_hrd','=',$lowongan->id_hrd)->orderBy('nama_posisi','asc')->get();
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $posisi = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
        }

        // View
        return view('lowongan/edit', [
        	'lowongan' => $lowongan,
        	'posisi' => $posisi,
        ]);
    }

    /**
     * Mengupdate lowongan di database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validasi
        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:255',
            'jabatan' => 'required',
            'status' => 'required',
            // 'deskripsi_lowongan' => 'required',
        ], validationMessages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // Jika tidak ada error
        else{
            // Menyimpan 
            $lowongan = Lowongan::find($request->id);
            $lowongan->judul_lowongan = $request->judul;
            // $lowongan->deskripsi_lowongan = htmlentities($request->deskripsi_lowongan);
            $lowongan->posisi = $request->jabatan;
            $lowongan->status = $request->status;
            $lowongan->save();
        }

        // Redirect
        return redirect('/admin/lowongan')->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Menghapus lowongan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Menghapus data
        $lowongan = Lowongan::find($request->id);
        $lowongan->delete();

        // Redirect
        return redirect('admin/lowongan')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Menampilkan data pelamar
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function applicant($id)
    {
        // Get data HRD dan lowongan
        if(Auth::user()->role == role_admin()){
            $lowongan = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->find($id);
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $lowongan = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('lowongan.id_hrd','=',$hrd->id_hrd)->find($id);
        }

        // Jika tidak ada data lowongan
        if(!$lowongan){
            abort(404);
        }
        // Jika ada data lowongan
        else{
            // Get data pelamar
            $pelamar = Pelamar::join('users','pelamar.id_user','=','users.id_user')->where('posisi','=',$lowongan->id_lowongan)->orderBy('pelamar_at','desc')->get();
            foreach($pelamar as $key=>$data){
                $seleksi = Seleksi::where('id_pelamar','=',$data->id_pelamar)->where('id_lowongan','=',$id)->first();
                if(!$seleksi){
                    $pelamar[$key]->badge_color = 'info';
                    $pelamar[$key]->hasil = 'Belum Diseleksi';
                }
                else{
                    if($seleksi->hasil == 0){
                        $pelamar[$key]->badge_color = 'danger';
                        $pelamar[$key]->hasil = 'Tidak Lolos';
                    }
                    elseif($seleksi->hasil == 1){
                        $pelamar[$key]->badge_color = 'success';
                        $pelamar[$key]->hasil = 'Lolos';
                    }
                    elseif($seleksi->hasil == 99){
                        $pelamar[$key]->badge_color = 'warning';
                        $pelamar[$key]->hasil = 'Belum Dites';
                    }
                }
                
                $pelamar[$key]->isKaryawan = $data->role == role_karyawan() ? true : false;
            }
        }

        // View
        return view('lowongan/applicant', [
            'lowongan' => $lowongan,
            'pelamar' => $pelamar,
        ]);
    }

    /**
     * Mengupdate status lowongan
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        // Mengupdate status
        $lowongan = Lowongan::find($request->id);
        $lowongan->status = $request->status;
        if($lowongan->save()){
            echo "Berhasil mengupdate status!";
        }
    }

    /**
     * Mengunjungi URL formulir yang aktif
     *
     * string $url
     * @return \Illuminate\Http\Response
     */
    public function visitForm($url)
    {
        // Get data
        $lowongan = Lowongan::where('url_lowongan','=',$url)->where('status','=',1)->first();

        // Jika tidak ada data
        if(!$lowongan){
        	abort(404);
        }
        
        // Session::put('url', $url);

        // Redirect
        return redirect('/lowongan/'.$url.'/daftar/step-1')->with(['posisi' => $lowongan->posisi]);
    }
}
