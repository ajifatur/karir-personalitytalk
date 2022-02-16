<?php

namespace App\Http\Controllers\API;

use Auth;
use File;
use Illuminate\Http\Request;
use App\Models\Lowongan;
use App\Models\HRD;
use App\Models\Pelamar;
use App\Models\Posisi;
use App\Models\Seleksi;
use Ajifatur\Helpers\DateTimeExt;

class VacancyController extends \App\Http\Controllers\Controller
{
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {		
        // Get vacancies
		if($request->query('status') == 'active')
        	$vacancies = Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('lowongan.status','=',1)->orderBy('status','desc')->orderBy('created_at','desc')->get();
		elseif($request->query('status') == 'inactive')
        	$vacancies = Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('lowongan.status','=',0)->orderBy('status','desc')->orderBy('created_at','desc')->get();
		else
        	$vacancies = Lowongan::join('hrd','lowongan.id_hrd','=','hrd.id_hrd')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('status','desc')->orderBy('created_at','desc')->get();

        // Loop vacancies
        $array = [];
        foreach($vacancies as $key=>$vacancy) {
            $array[$key]['id'] = $vacancy->id_lowongan;
            $array[$key]['title'] = $vacancy->judul_lowongan;
            $array[$key]['description'] = html_entity_decode($vacancy->deskripsi_lowongan);
            $array[$key]['excerpt'] = substr(strip_tags($array[$key]['description']),0,100).'...';
            $array[$key]['image'] = $vacancy->gambar_lowongan != '' && File::exists(public_path('assets/images/lowongan/'.$vacancy->gambar_lowongan)) ? asset('assets/images/lowongan/'.$vacancy->gambar_lowongan) : asset('assets/images/default-vacancy.png');
            $array[$key]['url'] = $vacancy->url_lowongan;
            $array[$key]['status'] = $vacancy->status;
            $array[$key]['author'] = $vacancy->nama_lengkap;
            $array[$key]['created_at'] = $vacancy->created_at;
            $array[$key]['date'] = DateTimeExt::full($vacancy->created_at).' WIB';
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
        $array['title'] = $vacancy->judul_lowongan;
        $array['description'] = html_entity_decode($vacancy->deskripsi_lowongan);
		$array['excerpt'] = substr(strip_tags($array['description']),0,100).'...';
		$array['image'] = $vacancy->gambar_lowongan != '' && File::exists(public_path('assets/images/lowongan/'.$vacancy->gambar_lowongan)) ? asset('assets/images/lowongan/'.$vacancy->gambar_lowongan) : asset('assets/images/default-vacancy.png');
        $array['url'] = $vacancy->url_lowongan;
        $array['status'] = $vacancy->status;
        $array['author'] = $vacancy->nama_lengkap;
        $array['created_at'] = $vacancy->created_at;
		$array['date'] = DateTimeExt::full($vacancy->created_at).' WIB';

        // Response
        return response()->json($array);
    }
}