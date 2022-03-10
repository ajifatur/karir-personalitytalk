<?php

namespace App\Http\Controllers\API;

use Auth;
use File;
use Illuminate\Http\Request;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\Vacancy;

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
            $vacancies = Vacancy::has('company')->has('position')->where('status','=',1)->orderBy('created_at','desc')->get();
		elseif($request->query('status') == 'inactive')
            $vacancies = Vacancy::has('company')->has('position')->where('status','=',0)->orderBy('created_at','desc')->get();
		else
            $vacancies = Vacancy::has('company')->has('position')->orderBy('status','desc')->orderBy('created_at','desc')->get();

        // Loop vacancies
        $array = [];
        foreach($vacancies as $key=>$vacancy) {
            $array[$key]['id'] = $vacancy->id;
            $array[$key]['title'] = $vacancy->name;
            $array[$key]['description'] = html_entity_decode($vacancy->description);
            $array[$key]['excerpt'] = substr(strip_tags($array[$key]['description']),0,100).'...';
            $array[$key]['image'] = $vacancy->image != '' && File::exists(public_path('assets/images/lowongan/'.$vacancy->image)) ? asset('assets/images/lowongan/'.$vacancy->image) : asset('assets/images/default-vacancy.png');
            $array[$key]['url'] = $vacancy->code;
            $array[$key]['status'] = $vacancy->status;
            $array[$key]['author'] = $vacancy->company->user->name;
            $array[$key]['company'] = $vacancy->company->name;
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
        $vacancy = Vacancy::has('company')->where('code','=',$url)->first();

        $array = [];
        if($vacancy) {
            $array['id'] = $vacancy->id;
            $array['title'] = $vacancy->name;
            $array['description'] = html_entity_decode($vacancy->description);
            $array['excerpt'] = substr(strip_tags($array['description']),0,100).'...';
            $array['image'] = $vacancy->image != '' && File::exists(public_path('assets/images/lowongan/'.$vacancy->image)) ? asset('assets/images/lowongan/'.$vacancy->image) : asset('assets/images/default-vacancy.png');
            $array['url'] = $vacancy->code;
            $array['status'] = $vacancy->status;
            $array['author'] = $vacancy->company->user->name;
            $array['company'] = $vacancy->company->name;
            $array['created_at'] = $vacancy->created_at;
            $array['date'] = DateTimeExt::full($vacancy->created_at).' WIB';
        }

        // Response
        return response()->json($array);
    }
}