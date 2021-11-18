<?php

namespace App\Http\Controllers\Test;

use Auth;
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
}