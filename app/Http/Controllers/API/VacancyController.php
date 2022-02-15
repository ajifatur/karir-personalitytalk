<?php

namespace App\Http\Controllers\API;

use Auth;
use Illuminate\Http\Request;
use App\Models\Lowongan;
use App\Models\HRD;
use App\Models\Pelamar;
use App\Models\Posisi;
use App\Models\Seleksi;

class VacancyController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get vacancies
        $vacancies = Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('status','desc')->orderBy('created_at','desc')->get();

        // Loop vacancies
        $array = [];
        foreach($vacancies as $key=>$vacancy) {
            $array[$key]['id'] = $vacancy->id_lowongan;
            $array[$key]['judul'] = $vacancy->judul_lowongan;
            $array[$key]['deskripsi'] = $vacancy->deskripsi_lowongan;
            $array[$key]['image'] = $vacancy->gambar_lowongan != '' ? asset('assets/images/lowongan/'.$vacancy->gambar_lowongan) : '';
            $array[$key]['url'] = $vacancy->url_lowongan;
            $array[$key]['status'] = $vacancy->status;
            $array[$key]['author'] = $vacancy->nama_lengkap;
            $array[$key]['created_at'] = $vacancy->created_at;
        }

        // Response
        return response()->json($array);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $url
     * @return \Illuminate\Http\Response
     */
    public function detail($url)
    {
        // Get the vacancy
        $vacancy = Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('url_lowongan','=',$url)->first();

        $array = [];
        $array['id'] = $vacancy->id_lowongan;
        $array['judul'] = $vacancy->judul_lowongan;
        $array['deskripsi'] = $vacancy->deskripsi_lowongan;
        $array['image'] = $vacancy->gambar_lowongan != '' ? asset('assets/images/lowongan/'.$vacancy->gambar_lowongan) : '';
        $array['url'] = $vacancy->url_lowongan;
        $array['status'] = $vacancy->status;
        $array['author'] = $vacancy->nama_lengkap;
        $array['created_at'] = $vacancy->created_at;

        // Response
        return response()->json($array);
    }
}