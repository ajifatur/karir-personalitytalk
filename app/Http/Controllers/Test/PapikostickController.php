<?php

namespace App\Http\Controllers\Test;

use Auth;
use PDF;
use Dompdf\FontMetrics;
use Illuminate\Http\Request;
use App\Models\Hasil;
use App\Models\Keterangan;

class PapikostickController extends \App\Http\Controllers\Controller
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
        $keterangan = Keterangan::where('id_paket','=',$result->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
        
        // Set the letters
        $huruf = ["N","G","A","L","P","I","T","V","X","S","B","O","R","D","C","Z","E","K","F","W"];

        // View
        return view('admin/result/papikostick/detail', [
            'result' => $result,
            'role' => $role,
            'user' => $user,
            'user_desc' => $user_desc,
            'huruf' => $huruf,
            'keterangan' => $keterangan,
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
        
        // Set the letters
        $huruf = ["N","G","A","L","P","I","T","V","X","S","B","O","R","D","C","Z","E","K","F","W"];
        
        // PDF
        $pdf = PDF::loadview('admin/result/papikostick/pdf', [
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
        
        return $pdf->stream($request->nama . '_' . $request->tes . '.pdf');
    }
}