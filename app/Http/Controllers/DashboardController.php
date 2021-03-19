<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Hasil;
use App\HRD;
use App\Kantor;
use App\Karyawan;
use App\Lowongan;
use App\Pelamar;
use App\Posisi;
use App\User;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // HRD
        $hrd = Auth::user()->role == role_admin() ? HRD::count() : HRD::where('id_user','=',Auth::user()->id_user)->first();

        // Perusahaan
        $perusahaan = Auth::user()->role == role_admin() ? HRD::count() : HRD::where('id_user','=',Auth::user()->id_user)->count();
        
        // Kantor
        $kantor = Auth::user()->role == role_admin() ? Kantor::count() : Kantor::where('id_hrd','=',$hrd->id_hrd)->count();
        
        // Karyawan
        $karyawan = Auth::user()->role == role_admin() ? Karyawan::join('users','karyawan.id_user','=','users.id_user')->where('status','=',1)->count() : Karyawan::join('users','karyawan.id_user','=','users.id_user')->where('id_hrd','=',$hrd->id_hrd)->where('status','=',1)->count();
        
        // Pelamar
        $pelamar = Auth::user()->role == role_admin() ? Pelamar::join('users','pelamar.id_user','=','users.id_user')->count() : Pelamar::join('users','pelamar.id_user','=','users.id_user')->where('id_hrd','=',$hrd->id_hrd)->count();
        
        // Posisi
        $posisi = Auth::user()->role == role_admin() ? Posisi::count() : Posisi::where('id_hrd','=',$hrd->id_hrd)->count();
        
        // Lowongan
        $lowongan = Auth::user()->role == role_admin() ? Lowongan::count() : Lowongan::where('id_hrd','=',$hrd->id_hrd)->count();
        
        // Hasil Tes Karyawan
        $hasil_karyawan = Auth::user()->role == role_admin() ? Hasil::join('users','hasil.id_user','=','users.id_user')->where('role','=',role_karyawan())->count() : Hasil::join('users','hasil.id_user','=','users.id_user')->where('id_hrd','=',$hrd->id_hrd)->where('role','=',role_karyawan())->count();
        
        // Hasil Tes Pelamar
        $hasil_pelamar = Auth::user()->role == role_admin() ? Hasil::join('users','hasil.id_user','=','users.id_user')->where('role','=',role_pelamar())->count() : Hasil::join('users','hasil.id_user','=','users.id_user')->where('id_hrd','=',$hrd->id_hrd)->where('role','=',role_pelamar())->count();

        // Hasil Tes Magang
        $hasil_magang = Auth::user()->role == role_admin() ? Hasil::join('users','hasil.id_user','=','users.id_user')->where('role','=',role_magang())->count() : 0;
        
        // View
        return view('dashboard/index', [
            'hrd' => $hrd,
            'perusahaan' => $perusahaan,
            'karyawan' => $karyawan,
            'pelamar' => $pelamar,
            'kantor' => $kantor,
            'posisi' => $posisi,
            'lowongan' => $lowongan,
            'hasil_karyawan' => $hasil_karyawan,
            'hasil_pelamar' => $hasil_pelamar,
            'hasil_magang' => $hasil_magang,
        ]);
    }
}
