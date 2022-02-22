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
        $keterangan = Keterangan::where('id_paket','=',$result->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);

        // Get the questions        
        $paket = PaketSoal::where('id_tes','=',$result->id_tes)->where('status','=',1)->first();
        $questions = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('soal.id_paket','=',$paket->id_paket)->orderBy('nomor','asc')->get();

        // Set categories
        $categories = ['Out','Me','Comp','Sci','Prs','Aesth','Lit','Mus','So. Se','Cler','Prac','Med'];

        // Set letters
        $letters = ['A','B','C','D','E','F','G','H','I'];

        // Set the sheet and sum
        $sheets = [];
        $sums = [];
        foreach($categories as $keyc=>$category) {
            $sums[$keyc] = 0;
            $i = $keyc;
            foreach($letters as $keyl=>$letter) {
                $sheets[$keyc][] = $result->hasil['answers'][($keyl+1)][$i];
                $sums[$keyc] += $result->hasil['answers'][($keyl+1)][$i];
                $i--;
                $i = $i < 0 ? 11 : $i;
            }
        }

        // Set the category ranks by ordered sums
        $ordered_sums = $sums;
        sort($ordered_sums);
		$occurences = array_count_values($sums);
        $category_ranks = [];
        foreach($sums as $keys=>$sum) {
            foreach($ordered_sums as $keyo=>$ordered_sum) {
                if($sum === $ordered_sum) {
					if($occurences[$sum] <= 1)
                    	$category_ranks[$keys] = $keyo + 1;
					else
                    	$category_ranks[$keys] = $keyo;
				}
            }
        }

        // Get interests
        $interests = [];
        foreach($category_ranks as $keyc=>$category_rank) {
            if($category_rank <= 3) {
                foreach($keterangan->keterangan as $note) {
                    if($note['code'] == $categories[$keyc]) {
						if(!array_key_exists($category_rank, $interests))
                        	$interests[$category_rank] = $note;
						else
                        	$interests[$category_rank + 1] = $note;
					}
                }
            }
        }
        ksort($interests);

        // View
        return view('admin/result/rmib/detail', [
            'result' => $result,
            'role' => $role,
            'user' => $user,
            'user_desc' => $user_desc,
            // 'keterangan' => $keterangan,
            'questions' => $questions,
            'categories' => $categories,
            'letters' => $letters,
            'sheets' => $sheets,
            'sums' => $sums,
            'category_ranks' => $category_ranks,
            'interests' => $interests,
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
        $result = Hasil::find($request->id_hasil);
        $result->hasil = json_decode($result->hasil, true);
        
        // Set the note
        $keterangan = Keterangan::where('id_paket','=',$result->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);

        // Get the questions        
        $paket = PaketSoal::where('id_tes','=',$result->id_tes)->where('status','=',1)->first();
        $questions = Soal::join('paket_soal','soal.id_paket','=','paket_soal.id_paket')->where('soal.id_paket','=',$paket->id_paket)->orderBy('nomor','asc')->get();

        // Set categories
        $categories = ['Out','Me','Comp','Sci','Prs','Aesth','Lit','Mus','So. Se','Cler','Prac','Med'];

        // Set letters
        $letters = ['A','B','C','D','E','F','G','H','I'];

        // Set the sheet and sum
        $sheets = [];
        $sums = [];
        foreach($categories as $keyc=>$category) {
            $sums[$keyc] = 0;
            $i = $keyc;
            foreach($letters as $keyl=>$letter) {
                $sheets[$keyc][] = $result->hasil['answers'][($keyl+1)][$i];
                $sums[$keyc] += $result->hasil['answers'][($keyl+1)][$i];
                $i--;
                $i = $i < 0 ? 11 : $i;
            }
        }

        // Set the category ranks by ordered sums
        $ordered_sums = $sums;
        sort($ordered_sums);
		$occurences = array_count_values($sums);
        $category_ranks = [];
        foreach($sums as $keys=>$sum) {
            foreach($ordered_sums as $keyo=>$ordered_sum) {
                if($sum === $ordered_sum) {
					if($occurences[$sum] <= 1)
                    	$category_ranks[$keys] = $keyo + 1;
					else
                    	$category_ranks[$keys] = $keyo;
				}
            }
        }

        // Get interests
        $interests = [];
        foreach($category_ranks as $keyc=>$category_rank) {
            if($category_rank <= 3) {
                foreach($keterangan->keterangan as $note) {
                    if($note['code'] == $categories[$keyc]) {
						if(!array_key_exists($category_rank, $interests))
                        	$interests[$category_rank] = $note;
						else
                        	$interests[$category_rank + 1] = $note;
					}
                }
            }
        }
        ksort($interests);
        
        // PDF
        $pdf = PDF::loadview('admin/result/rmib/pdf', [
            'result' => $result,
            'image' => $request->image,
            'nama' => $request->nama,
            'usia' => $request->usia,
            'jenis_kelamin' => $request->jenis_kelamin,
            'posisi' => $request->posisi,
            'tes' => $request->tes,
            // 'keterangan' => $keterangan,
            'questions' => $questions,
            'categories' => $categories,
            'letters' => $letters,
            'sheets' => $sheets,
            'sums' => $sums,
            'category_ranks' => $category_ranks,
            'interests' => $interests,
        ]);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream($request->nama . '_' . $request->tes . '.pdf');
    }
}