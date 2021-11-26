<?php

namespace App\Http\Controllers\Test;

use Auth;
use Illuminate\Http\Request;
use App\Models\Keterangan;

class ISTController extends \App\Http\Controllers\Controller
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

        // Get the result
        $resultA = $result->hasil;

        // IQ Category
        $kategoriIQ = '';
        if($result['IQ'] <= 80) $kategoriIQ = 'Dibawah Rata-Rata';
        elseif($result['IQ'] >= 81 && $result['IQ'] <= 94) $kategoriIQ = 'Rata-Rata Bawah';
        elseif($result['IQ'] >= 95 && $result['IQ'] <= 99) $kategoriIQ = 'Rata-Rata';
        elseif($result['IQ'] >= 100 && $result['IQ'] <= 104) $kategoriIQ = 'Rata-Rata Atas';
        elseif($result['IQ'] >= 105 && $result['IQ'] <= 118) $kategoriIQ = 'Superior';
        elseif($result['IQ'] >= 119) $kategoriIQ = 'Sangat Superior';

        // View
        return view('admin/result/ist/detail', [
            'result' => $result,
            'role' => $role,
            'user' => $user,
            'user_desc' => $user_desc,
            'resultA' => $resultA,
            'kategoriIQ' => $kategoriIQ
        ]);
    }
}