<?php

namespace App\Http\Controllers\Test;

use Auth;
use PDF;
use Dompdf\FontMetrics;
use Illuminate\Http\Request;
use App\Models\Description;

class DISC2Controller extends \App\Http\Controllers\Controller
{
    /**
     * Display the specified resource.
     *
     * @param  object  $result
     * @return \Illuminate\Http\Response
     */
    public static function detail($result)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Set the diff
        $array_selisih = [
            'D' => $result->result['dm'] - $result->result['dl'],
            'I' => $result->result['im'] - $result->result['il'],
            'S' => $result->result['sm'] - $result->result['sl'],
            'C' => $result->result['cm'] - $result->result['cl'],
        ];

        // Array 1
        $array_1 = [
            0   => [-6, -7, -5.7, -6],
            1   => [-5.3, -4.6, -4.3, -4.7],
            2   => [-4, -2.5, -3.5, -3.5],
            3   => [-2.5, -1.3, -1.5, -1.5],
            4   => [-1.7, 1, -0.7, 0.5],
            5   => [-1.3, 3, 0.5, 2],
            6   => [0, 3.5, 1, 3],
            7   => [0.5, 5.3, 2.5, 5.3],
            8   => [1, 5.7, 3, 5.7],
            9   => [2, 6, 4, 6],
            10  => [3, 6.5, 4.6, 6.3],
            11  => [3.5, 7, 5, 6.5],
            12  => [4, 7, 5.7, 6.7],
            13  => [4.7, 7, 6, 7],
            14  => [5.3, 7, 6.5, 7.3],
            15  => [6.5, 7, 6.5, 7.3],
            16  => [7, 7.5, 7, 7.3],
            17  => [7, 7.5, 7, 7.5],
            18  => [7, 7.5, 7, 8],
            19  => [7.5, 7.5, 7.5, 8],
            20  => [7.5, 8, 7.5, 8],
        ];

        // Array 2
        $array_2 = [
            0   => [7.5, 7, 7.5, 7.5],
            1   => [6.5, 6, 7, 7],
            2   => [4.3, 4, 6, 5.6],
            3   => [2.5, 2.5, 4, 4],
            4   => [1.5, 0.5, 2.5, 2.5],
            5   => [0.5, 0, 1.5, 1.5],
            6   => [0, -2, 0.5, 0.5],
            7   => [-1.3, -3.5, -1.3, 0],
            8   => [-1.5, -4.3, -2, -1.3],
            9   => [-2.5, -5.3, -3, -2.5],
            10  => [-3, -6, -4.3, -3.5],
            11  => [-3.5, -6.5, -5.3, -5.3],
            12  => [-4.3, -7, -6, -5.7],
            13  => [-5.3, -7.2, -6.5, -6],
            14  => [-5.7, -7.2, -6.7, -6.5],
            15  => [-6, -7.2, -6.7, -7],
            16  => [-6.5, -7.3, -7, -7.3],
            17  => [6.7, -7.3, -7.2, -7.5],
            18  => [7, -7.3, -7.3, -7.7],
            19  => [-7.3, -7.5, -7.5, -7.9],
            20  => [-7.5, -8, -8, -8],
        ];

        // Array 3
        $array_3 = [
            -22 => [-8, -8, -8, -7.5],
            -21 => [-7.5, -8, -8, -7.3],
            -20 => [-7, -8, -8, -7.3],
            -19 => [-6.8, -8, -8, -7],
            -18 => [-6.75, -7, -7.5, -6.7],
            -17 => [-6.7, -6.7, -7.3, -6.7],
            -16 => [-6.5, -6.7, -7.3, -6.7],
            -15 => [-6.3, -6.7, -7, -6.5],
            -14 => [-6.1, -6.7, -6.5, -6.3],
            -13 => [-5.9, -6.7, -6.5, -6],
            -12 => [-5.7, -6.7, -6.5, -5.85],
            -11 => [-5.3, -6.7, -6.5, -5.85],
            -10 => [-4.3, -6.5, -6, -5.7],
            -9  => [-3.5, -6, -4.7, -4.7],
            -8  => [-3.25, -5.7, -4.3, -4.3],
            -7  => [-3, -4.7, -3.5, -3.5],
            -6  => [-2.75, -4.3, -3, -3],
            -5  => [-2.5, -3.5, -2, -2.5],
            -4  => [-1.5, -3, -1.5, -0.5],
            -3  => [-1, -2, -1, 0],
            -2  => [-0.5, -1.5, -0.5, 0.3],
            -1  => [-0.25, 0, 0, 0.5],
            0   => [0, 0.5, 1, 1.5],
            1   => [0.5, 1, 1.5, 3],
            2   => [0.7, 1.5, 2, 4],
            3   => [1, 3, 3, 4.3],
            4   => [1.3, 4, 3.5, 5.5],
            5   => [1.5, 4.3, 4, 5.7],
            6   => [2, 5, 0, 6], // S is empty
            7   => [2.5, 5.5, 4.7, 6.3],
            8   => [3.5, 6.5, 5, 6.5],
            9   => [4, 6.7, 5.5, 6.7],
            10  => [4.7, 7, 6, 7],
            11  => [4.85, 7.3, 6.2, 7.3],
            12  => [5, 7.3, 6.3, 7.3],
            13  => [5.5, 7.3, 6.5, 7.3],
            14  => [6, 7.3, 6.7, 7.3],
            15  => [6.3, 7.3, 7, 7.3],
            16  => [6.5, 7.3, 7.3, 7.3],
            17  => [6.7, 7.3, 7.3, 7.5],
            18  => [7, 7.5, 7.3, 8],
            19  => [7.3, 8, 7.3, 8],
            20  => [7.3, 8, 7.5, 8],
            21  => [7.5, 8, 8, 8],
            22  => [8, 8, 8, 8],
        ];

        // Graph
        $graph = [
            1 => [
                'D' => $array_1[$result->result['dm']][0],
                'I' => $array_1[$result->result['im']][1],
                'S' => $array_1[$result->result['sm']][2],
                'C' => $array_1[$result->result['cm']][3],
            ],
            2 => [
                'D' => $array_2[$result->result['dl']][0],
                'I' => $array_2[$result->result['il']][1],
                'S' => $array_2[$result->result['sl']][2],
                'C' => $array_2[$result->result['cl']][3],
            ],
            3 => [
                'D' => $array_3[$array_selisih['D']][0],
                'I' => $array_3[$array_selisih['I']][1],
                'S' => $array_3[$array_selisih['S']][2],
                'C' => $array_3[$array_selisih['C']][3],
            ],
        ];

        // Set the personality
        $array_kepribadian = [
            'most' => [],
            'least' => [],
            'change' => [],
        ];
        for($i = 0; $i < 40; $i++) {
            array_push($array_kepribadian['most'], analisis_disc_24($i + 1, $graph[1]['D'], $graph[1]['I'], $graph[1]['S'], $graph[1]['C']));
            array_push($array_kepribadian['least'], analisis_disc_24($i + 1, $graph[2]['D'], $graph[2]['I'], $graph[2]['S'], $graph[2]['C']));
            array_push($array_kepribadian['change'], analisis_disc_24($i + 1, $graph[3]['D'], $graph[3]['I'], $graph[3]['S'], $graph[3]['C']));
        }

        // Index
        $index = [
            'most' => [],
            'least' => [],
            'change' => [],
        ];
        foreach($array_kepribadian['most'] as $key=>$value) {
            if($value == 1) array_push($index['most'], $key);
        }
        foreach($array_kepribadian['least'] as $key=>$value) {
            if($value == 1) array_push($index['least'], $key);
        }
        foreach($array_kepribadian['change'] as $key=>$value) {
            if($value == 1) array_push($index['change'], $key);
        }

        // Set the description
        $description = Description::where('packet_id','=',$result->packet_id)->first();
        $description->description = json_decode($description->description, true);

        // View
        return view('admin/result/disc-2/detail', [
            'result' => $result,
            'array_selisih' => $array_selisih,
            'graph' => $graph,
            'index' => $index,
            'description' => $description,
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
        // Set the DISC
        $disc = array('D', 'I', 'S','C');
		
		// Set the index
		$index = json_decode($request->index, true);

        // Set the note
        $keterangan = Keterangan::where('id_paket','=',$request->id_paket)->first();
        $keterangan->keterangan = json_decode($keterangan->keterangan, true);
		
		// Set the MOST, LEAST, CHANGE
		$most = $keterangan->keterangan[$index['most'][0]];
		$least = $keterangan->keterangan[$index['least'][0]];
		$change = $keterangan->keterangan[$index['change'][0]];
        
        // PDF
        $pdf = PDF::loadview('admin/result/disc-2/pdf', [
            'mostChartImage' => $request->mostChartImage,
            'leastChartImage' => $request->leastChartImage,
            'changeChartImage' => $request->changeChartImage,
            'nama' => $request->nama,
            'usia' => $request->usia,
            'jenis_kelamin' => $request->jenis_kelamin,
            'posisi' => $request->posisi,
            'tes' => $request->tes,
            'hasil' => $request->result,
            'array_selisih' => $request->array_selisih,
            'index' => $request->index,
            'disc' => $disc,
            'most' => $most,
            'least' => $least,
            'change' => $change,
        ]);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream($request->nama . '_' . $request->tes . '.pdf');
    }
}