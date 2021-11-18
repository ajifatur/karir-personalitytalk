<?php

namespace App\Http\Controllers\Test;

use Auth;
use PDF;
use Dompdf\FontMetrics;
use Illuminate\Http\Request;
use App\Models\Keterangan;

class DISC1Controller extends \App\Http\Controllers\Controller
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
        
        // Set the result
        $disc = array('D', 'I', 'S','C');
        $m_score = $result->hasil['M'];
        $l_score = $result->hasil['L'];

        // Set the ranking
        $disc_score_m = sortScore($m_score);
        $disc_score_l = sortScore($l_score);

        // Set the code
        $code_m = setCode($disc_score_m);
        $code_l = setCode($disc_score_l);

        // Set the note
        $keterangan = Keterangan::where('id_paket','=',$result->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
        $kode_keterangan = substr($code_l[0],1,1);
        switch($kode_keterangan) {
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
        return view('admin/result/disc-1/detail', [
            'result' => $result,
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
     * Print to PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request)
    {
        // DISC
        $disc = array('D', 'I', 'S','C');

        // Set the note
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
        $pdf = PDF::loadview('admin/result/disc-1/pdf', [
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
        
        return $pdf->stream("Result.pdf");
    }
}