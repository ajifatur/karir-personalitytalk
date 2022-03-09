<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Result;
use App\Models\Company;
use App\Models\Test;
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
        // Check the access
        has_access(method(__METHOD__), Auth::user()->role_id);

        if($request->ajax()) {
            // Get employee or applicant results
            if($request->query('role') == role('employee') || $request->query('role') == role('applicant')) {
                // Get the company, test, role
                $company = Auth::user()->role->is_global === 1 ? Company::find($request->query('company')) : Company::find(Auth::user()->attribute->company_id);
                $test = Test::find($request->query('test'));
                $role = $request->query('role');

                if($company && $test)
                    $results = Result::has('company')->has('test')->has('packet')->whereHas('user', function (Builder $query) use ($role) {
                        return $query->where('role_id','=',$role);
                    })->where('company_id','=',$company->id)->where('test_id','=',$test->id)->get();
                elseif($company && !$test)
                    $results = Result::has('company')->has('test')->has('packet')->whereHas('user', function (Builder $query) use ($role) {
                        return $query->where('role_id','=',$role);
                    })->where('company_id','=',$company->id)->get();
                elseif(!$company && $test)
                    $results = Result::has('company')->has('test')->has('packet')->whereHas('user', function (Builder $query) use ($role) {
                        return $query->where('role_id','=',$role);
                    })->where('test_id','=',$test->id)->get();
                else
                    $results = Result::has('company')->has('test')->has('packet')->whereHas('user', function (Builder $query) use ($role) {
                        return $query->where('role_id','=',$role);
                    })->get();
            }

            // Set
            if(count($results) > 0) {
                foreach($results as $key=>$result) {
                    $user = $result->user()->has('attribute')->first();
                    $results[$key]->user = $user;
                    $results[$key]->position_name = $user->attribute->position ? $user->attribute->position->name : '-';
                    $results[$key]->test_name = $result->test->name;
                    $results[$key]->company_name = $result->company->name;
                }
            }

            // Return
            return DataTables::of($results)
                ->addColumn('checkbox', '<input type="checkbox" class="form-check-input checkbox-one">')
                ->editColumn('user', '
                    <span class="d-none">{{ $user->name }}</span>
                    <a href="{{ route(\'admin.result.detail\', [\'id\' => $id]) }}">{{ ucwords($user->name) }}</a>
                    <br>
                    <small class="text-muted">{{ $user->username }}</small>
                ')
                ->addColumn('datetime', '
                    <span class="d-none">{{ $created_at != null ? $created_at : "" }}</span>
                    {{ $created_at != null ? date("d/m/Y", strtotime($created_at)) : "-" }}
                    <br>
                    <small class="text-muted">{{ date("H:i", strtotime($created_at))." WIB" }}</small>
                ')
                ->addColumn('options', '
                    <div class="btn-group">
                        <a href="{{ route(\'admin.result.detail\', [\'id\' => $id]) }}" class="btn btn-sm btn-info" data-id="{{ $id }}" data-bs-toggle="tooltip" title="Lihat Detail"><i class="bi-eye"></i></a>
                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                    </div>
                ')
                ->rawColumns(['checkbox', 'user', 'datetime', 'options'])
                ->make(true);
        }

        // Auto redirect to employee results
        if(!in_array($request->query('role'), [role('employee'), role('applicant')])) {
            return redirect()->route('admin.result.index', ['role' => role('employee')]);
        }

        // Get tests
        $tests = Test::orderBy('name','asc')->get();

        // Get companies
        $companies = Company::orderBy('name','asc')->get();

        // View
        return view('admin/result/index', [
            'tests' => $tests,
            'companies' => $companies
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
        has_access(method(__METHOD__), Auth::user()->role_id);

    	// Get the result
    	if(Auth::user()->role->is_global === 1) {
            $result = Result::has('user')->has('company')->has('test')->has('packet')->findOrFail($id);
        }
        elseif(Auth::user()->role->is_global === 0) {
            $company = Company::find(Auth::user()->attribute->company_id);
            $result = Result::has('user')->has('company')->has('test')->has('packet')->where('company_id','=',$company->id)->findOrFail($id);
        }

        // JSON decode
        $result->result = json_decode($result->result, true);

        // DISC 1.0
        if($result->test->code == 'disc-40-soal')
            return DISC1Controller::detail($result);
        // DISC 2.0
        elseif($result->test->code == 'disc-24-soal')
            return DISC2Controller::detail($result);
        // IST
        elseif($result->test->code == 'ist')
            return ISTController::detail($result);
        // MSDT
        elseif($result->test->code == 'msdt')
            return MSDtController::detail($result);
        // Papikostick
        elseif($result->test->code == 'papikostick')
            return PapikostickController::detail($result);
        // SDI
        elseif($result->test->code == 'sdi')
            return SDIController::detail($result);
        // RMIB 1.0
        elseif($result->test->code == 'rmib')
            return RMIBController::detail($result);
        // RMIB 2.0
        elseif($result->test->code == 'rmib-2')
            return RMIBController::detail($result);
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
        has_access(method(__METHOD__), Auth::user()->role_id);
        
        // Get the result
        $result = Result::findOrFail($request->id);

        // Delete the result
        $result->delete();

        // Redirect
        return redirect()->route('admin.result.index', ['role' => $request->role])->with(['message' => 'Berhasil menghapus data.']);
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
        has_access(method(__METHOD__), Auth::user()->role_id);
		
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
        // RMIB 1.0
        elseif($request->path == 'rmib')
            return RMIBController::print($request);
        // RMIB 2.0
        elseif($request->path == 'rmib-2')
            return RMIBController::print($request);
    }
}