<?php

namespace App\Http\Controllers\Update;

use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Hasil;
use App\Models\HRD;
use App\Models\Karyawan;
use App\Models\Keterangan;
use App\Models\Lowongan;
use App\Models\Pelamar;
use App\Models\Posisi;
use App\Models\Role;
use App\Models\Seleksi;
use App\Models\Tes;
use App\Models\User;
use App\Http\Controllers\Test\DISC1Controller;
use App\Http\Controllers\Test\DISC2Controller;
use App\Http\Controllers\Test\ISTController;
use App\Http\Controllers\Test\MSDTController;
use App\Http\Controllers\Test\PapikostickController;
use App\Http\Controllers\Test\SDIController;
use App\Http\Controllers\Test\RMIBController;

class ResultController extends \App\Http\Controllers\Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            // Get employee results
            if($request->query('role') == role('employee')) {
                // Get the HRD and the test
                $hrd = Auth::user()->role == role('admin') ? HRD::find($request->query('hrd')) : HRD::where('id_user','=',Auth::user()->id_user)->first();
                $test = Tes::find($request->query('test'));

                if($hrd && $test)
                    $results = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role('employee'))->where('hasil.id_hrd','=',$hrd->id_hrd)->where('hasil.id_tes','=',$test->id_tes)->get();
                elseif($hrd && !$test)
                    $results = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role('employee'))->where('hasil.id_hrd','=',$hrd->id_hrd)->get();
                elseif(!$hrd && $test)
                    $results = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role('employee'))->where('hasil.id_tes','=',$test->id_tes)->get();
                else
                    $results = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role('employee'))->get();
            }
            // Get applicant results
            elseif($request->query('role') == role('applicant')) {
                // Get the HRD and the test
                $hrd = Auth::user()->role == role('admin') ? HRD::find($request->query('hrd')) : HRD::where('id_user','=',Auth::user()->id_user)->first();
                $test = Tes::find($request->query('test'));

                if($hrd && $test)
                    $results = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role('applicant'))->where('hasil.id_hrd','=',$hrd->id_hrd)->where('hasil.id_tes','=',$test->id_tes)->get();
                elseif($hrd && !$test)
                    $results = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role('applicant'))->where('hasil.id_hrd','=',$hrd->id_hrd)->get();
                elseif(!$hrd && $test)
                    $results = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role('applicant'))->where('hasil.id_tes','=',$test->id_tes)->get();
                else
                    $results = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->join('users','hasil.id_user','=','users.id_user')->where('users.role','=',role('applicant'))->get();
            }

            // Set
            if(count($results)>0) {
                foreach($results as $key=>$result) {
                    if($request->query('role') == role('employee')) {
                        $employee = Karyawan::join('posisi','karyawan.posisi','=','posisi.id_posisi')->where('id_user','=',$result->id_user)->first();
                        $results[$key]->posisi = $employee ? $employee->nama_posisi : '-';
                    }
                    elseif($request->query('role') == role('applicant')) {
                        $applicant = $result->role == role('applicant') ? Pelamar::where('id_user','=',$result->id_user)->first() : null;
                        $position = $applicant ? Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('lowongan.id_lowongan','=',$applicant->posisi)->first() : null;
                        $results[$key]->posisi = $position ? $position->nama_posisi : '-';
                    }
                }
            }

            // Return
            return DataTables::of($results)
                ->addColumn('checkbox', '<input type="checkbox" class="form-check-input checkbox-one">')
                ->addColumn('name', '
                    <span class="d-none">{{ $nama_user }}</span>
                    <a href="{{ route(\'admin.result.detail\', [\'id\' => $id_hasil]) }}">{{ ucwords($nama_user) }}</a>
                    <br>
                    <small class="text-muted">{{ $username }}</small>
                ')
                ->editColumn('posisi', '
                    {{ $posisi }}
                ')
                ->addColumn('datetime', '
                    <span class="d-none">{{ $test_at != null ? $test_at : "" }}</span>
                    {{ $test_at != null ? date("d/m/Y", strtotime($test_at)) : "-" }}
                    <br>
                    <small class="text-muted">{{ date("H:i", strtotime($test_at))." WIB" }}</small>
                ')
                ->addColumn('tes', '
                    {{ $nama_tes }}
                ')
                ->addColumn('company', '
                    {{ get_perusahaan_name($id_hrd) }}
                ')
                ->addColumn('options', '
                    <div class="btn-group">
                        <a href="{{ route(\'admin.result.detail\', [\'id\' => $id_hasil]) }}" class="btn btn-sm btn-info" data-id="{{ $id_hasil }}" data-bs-toggle="tooltip" title="Lihat Detail"><i class="bi-eye"></i></a>
                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $id_hasil }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                    </div>
                ')
                ->removeColumn('password')
                ->rawColumns(['checkbox', 'name', 'posisi', 'datetime', 'tes', 'company', 'options'])
                ->make(true);
        }

        // Auto redirect to employee results
        if(!in_array($request->query('role'), [role('employee'), role('applicant')])) {
            return redirect()->route('admin.result.index', ['role' => role('employee')]);
        }

        // Get tests
        $tests = Tes::orderBy('nama_tes','asc')->get();

        // Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // View
        return view('admin/result/index', [
            'tests' => $tests,
            'hrds' => $hrds
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

    	// Get the result
    	if(Auth::user()->role == role('admin')) {
            $result = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->findOrFail($id);
        }
        else {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $result = Hasil::join('tes','hasil.id_tes','=','tes.id_tes')->where('id_hasil','=',$id)->where('hasil.id_hrd','=',$hrd->id_hrd)->firstOrFail();
        }

        if($result) {
            $test = Tes::find($result->id_tes);
            if($result->path != 'disc-40-soal' && $result->path != 'disc-24-soal' && $result->path != 'papikostick' && $result->path != 'sdi' && $result->path != 'msdt' && $result->path != 'ist' && $result->path != 'rmib' && $result->path != 'rmib-2') abort(404);
            $result->hasil = json_decode($result->hasil, true);

        	// Get the user, the user description, and the role
        	$user = User::find($result->id_user);
        	$role = Role::find($user->role);
			if($user->role == role('employee'))
                $user_desc = Karyawan::join('posisi','karyawan.posisi','=','posisi.id_posisi')->where('id_user','=',$result->id_user)->firstOrFail();
        	elseif($user->role == role('applicant'))
                $user_desc = Pelamar::join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_user','=',$result->id_user)->firstOrFail();
        	else
                $user_desc = [];
        }

        // DISC 1.0
        if($result->path == 'disc-40-soal')
            return DISC1Controller::detail($result, $user, $user_desc, $role);
        // DISC 2.0
        elseif($result->path == 'disc-24-soal')
            return DISC2Controller::detail($result, $user, $user_desc, $role);
        // IST
        elseif($result->path == 'ist')
            return ISTController::detail($result, $user, $user_desc, $role);
        // MSDT
        elseif($result->path == 'msdt')
            return MSDtController::detail($result, $user, $user_desc, $role);
        // Papikostick
        elseif($result->path == 'papikostick')
            return PapikostickController::detail($result, $user, $user_desc, $role);
        // SDI
        elseif($result->path == 'sdi')
            return SDIController::detail($result, $user, $user_desc, $role);
        // RMIB
        elseif($result->path == 'rmib' || $result->path == 'rmib-2')
            return RMIBController::detail($result, $user, $user_desc, $role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the result
        $result = Hasil::join('users','hasil.id_user','=','users.id_user')->find($request->id);

        // Delete the result
        $result->delete();

        // Redirect
        return redirect()->route('admin.result.index', ['role' => $result->role])->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Print to PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);
		
        ini_set('max_execution_time', '300');
		
        // DISC 1.0
        if($request->path == 'disc-40-soal')
            return DISC1Controller::print($request);
        // DISC 2.0
        elseif($request->path == 'disc-24-soal')
            return DISC2Controller::print($request);
        // IST
        elseif($request->path == 'ist')
            abort(404);
            // return ISTController::print($request);
        // MSDT
        elseif($request->path == 'msdt')
            return MSDtController::print($request);
        // Papikostick
        elseif($request->path == 'papikostick')
            return PapikostickController::print($request);
        // SDI
        elseif($request->path == 'sdi')
            return SDIController::print($request);
        // RMIB
        elseif($request->path == 'rmib' || $request->path == 'rmib-2')
            return RMIBController::print($request);
    }
}