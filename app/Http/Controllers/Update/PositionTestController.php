<?php

namespace App\Http\Controllers\Update;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Posisi;
use App\Models\HRD;
use App\Models\Tes;

class PositionTestController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check the access
        has_access(method(__METHOD__), Auth::user()->role_id);

        if(Auth::user()->role->is_global === 1) {
            // Get the HRD
            $hrd = HRD::find($request->query('hrd'));
            
            // Get tests
            $tests = [];
            $testArray = [];
            if($hrd && $hrd->akses_tes != '') {
                $ids = explode(',', $hrd->akses_tes);
                $testArray = $ids;
                foreach($ids as $id) {
                    $test = Tes::find($id);
                    if($test) {
                        array_push($tests, $test);
                    }
                }
            }

            // Get positions
            $positions = $hrd ? Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get() : [];
        }
        elseif(Auth::user()->role->is_global === 0) {
            // Get the HRD
            $hrd = HRD::where('id_user','=',Auth::user()->id)->firstOrFail();

            // Get tests
            $tests = [];
            $testArray = [];
            if($hrd && $hrd->akses_tes != '') {
                $ids = explode(',', $hrd->akses_tes);
                $testArray = $ids;
                foreach($ids as $id) {
                    $test = Tes::find($id);
                    if($test) {
                        array_push($tests, $test);
                    }
                }
            }

            // Get positions
            $positions = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
        }

        // Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // View
        return view('admin/position-test/index', [
            'hrds' => $hrds,
            'tests' => $tests,
            'positions' => $positions,
            'testArray' => $testArray,
        ]);
    }

    /**
     * Change the resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        // Get the position
        $position = Posisi::find($request->position);

        // Get the test
        $test = Tes::find($request->test);

        // Change status
        if($position && $test) {
            $ids = explode(',', $position->tes);

            // Add to position if true
            if($request->isChecked == 1) {
                if(!in_array($test->id_tes, $ids)) {
                    array_push($ids, $test->id_tes);
                }
            }
            // Remove from position if false
            else {
                $key = array_search($test->id_tes, $ids);
                if($key !== false) {
                    unset($ids[$key]);
                }
            }

            // Update the position
            $position->tes = implode(',', $ids);
            $position->save();

            echo 'Berhasil mengganti status.';
        }
        else {
            echo 'Tidak dapat mengganti status.';
        }
    }
}