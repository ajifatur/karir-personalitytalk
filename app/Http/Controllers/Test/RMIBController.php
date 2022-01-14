<?php

namespace App\Http\Controllers\Test;

use Auth;
use PDF;
use Dompdf\FontMetrics;
use Illuminate\Http\Request;
use App\Models\Hasil;
use App\Models\Keterangan;
use App\Models\PaketSoal;
use App\Models\Soal;

class RMIBController extends \App\Http\Controllers\Controller
{
    /**
     * Display the specified resource.
     *
     * @param  object  $result
     * @param  object  $user
     * @param  object  $user_desc
     * @param  object  $role
     * @return \Illuminate\Http\Response
     */
    public static function detail($result, $user, $user_desc, $role)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Set the note
        // $keterangan = Keterangan::where('id_paket','=',$result->id_paket)->first();
        // $keterangan->keterangan = json_decode($keterangan->keterangan, true);

        // Get the questions        
        $paket = PaketSoal::where('id_tes','=',$result->id_tes)->where('status','=',1)->first();
        $questions = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('soal.id_paket','=',$paket->id_paket)->orderBy('nomor','asc')->get();

        // Set letters
        $letters = ['A','B','C','D','E','F','G','H','I'];

        // View
        return view('admin/result/rmib/detail', [
            'result' => $result,
            'role' => $role,
            'user' => $user,
            'user_desc' => $user_desc,
            'questions' => $questions,
            'letters' => $letters,
            // 'keterangan' => $keterangan,
        ]);
    }

    /**
     * Print to PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request)
    {
        // Set the result
        $hasil = Hasil::find($request->id_hasil);
        $hasil->hasil = json_decode($hasil->hasil, true);
        
        // Set the note
        $keterangan = Keterangan::where('id_paket','=',$hasil->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
        
        // PDF
        $pdf = PDF::loadview('admin/result/rmib/pdf', [
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
        
        return $pdf->stream("Result.pdf");
    }
}