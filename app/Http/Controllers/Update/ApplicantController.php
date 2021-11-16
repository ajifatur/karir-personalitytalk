<?php

namespace App\Http\Controllers\Update;

use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Agama;
use App\Models\HRD;
use App\Models\Karyawan;
use App\Models\Lowongan;
use App\Models\Pelamar;
use App\Models\Seleksi;
use App\Models\User;

class ApplicantController extends \App\Http\Controllers\Controller
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
            // Get applicants
            if(Auth::user()->role == role('admin')) {
                $hrd = HRD::find($request->query('hrd'));
                $applicants = $hrd ? Pelamar::join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('pelamar.id_hrd','=',$hrd->id_hrd)->orderBy('pelamar_at','desc')->get() : Pelamar::join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->orderBy('pelamar_at','desc')->get();
            }
            elseif(Auth::user()->role == role('hrd')) {
                $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
                $applicants = Pelamar::join('users','pelamar.id_user','=','users.id_user')->join('lowongan','pelamar.posisi','=','lowongan.id_lowongan')->join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('pelamar.id_hrd','=',$hrd->id_hrd)->orderBy('pelamar_at','desc')->get();
            }

            // Return
            return DataTables::of($applicants)
                ->addColumn('checkbox', '<input type="checkbox" class="form-check-input checkbox-one">')
                ->addColumn('name', '
                    <span class="d-none">{{ $nama_user }}</span>
                    <a href="{{ route(\'admin.applicant.detail\', [\'id\' => $id_pelamar]) }}">{{ ucwords($nama_user) }}</a>
                    <br>
                    <small class="text-muted"><i class="bi-envelope me-2"></i>{{ $email }}</small>
                    <br>
                    <small class="text-muted"><i class="bi-phone me-2"></i>{{ $nomor_hp }}</small>
                ')
                ->editColumn('posisi', '
                    {{ get_posisi_name($posisi) }}
                ')
                ->addColumn('datetime', '
                    <span class="d-none">{{ $pelamar_at != null ? $pelamar_at : "" }}</span>
                    {{ $pelamar_at != null ? date("d/m/Y", strtotime($pelamar_at)) : "-" }}
                    <br>
                    <small class="text-muted">{{ date("H:i", strtotime($pelamar_at))." WIB" }}</small>
                ')
                ->addColumn('company', '
                    {{ get_perusahaan_name($id_hrd) }}
                ')
                ->addColumn('options', '
                    <div class="btn-group">
                        <a href="{{ route(\'admin.applicant.detail\', [\'id\' => $id_pelamar]) }}" class="btn btn-sm btn-info" data-id="{{ $id_pelamar }}" data-bs-toggle="tooltip" title="Lihat Detail"><i class="bi-eye"></i></a>
                        <a href="/admin/pelamar/edit/{{ $id_pelamar }}" class="btn btn-sm btn-warning" data-id="{{ $id_pelamar }}" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $id_pelamar }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                    </div>
                ')
                ->removeColumn('password')
                ->rawColumns(['checkbox', 'name', 'username', 'posisi', 'datetime', 'company', 'options'])
                ->make(true);
        }

        // Get HRDs
        $hrds = HRD::orderBy('perusahaan','asc')->get();

        // View
        return view('admin/applicant/index', [
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
        
        // Get the applicant
        if(Auth::user()->role == role('admin'))
            $applicant = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->firstOrFail();
        else {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
    	    $applicant = Pelamar::join('agama','pelamar.agama','=','agama.id_agama')->where('id_pelamar','=',$id)->where('id_hrd','=',$hrd->id_hrd)->firstOrFail();
        }

        if($applicant) {
            // Get the vacancy
            $applicant->posisi = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_lowongan','=',$applicant->posisi)->first();
			
			// Get the user
			$applicant->user = User::find($applicant->id_user);

            // Get the selection
            $selection = Seleksi::where('id_pelamar','=',$id)->where('id_lowongan','=',$applicant->posisi->id_lowongan)->first();

            // Set the applicant attribute
            $applicant->akun_sosmed = json_decode($applicant->akun_sosmed, true);
            $applicant->data_darurat = json_decode($applicant->data_darurat, true);
            $applicant->keahlian = json_decode($applicant->keahlian, true);
        }

        // View
        return view('admin/applicant/detail', [
            'applicant' => $applicant,
            'selection' => $selection
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        abort(404);

        // Get the vacancy
    	if(Auth::user()->role == role('hrd')) {
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $vacancy = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->where('id_lowongan','=',$id)->where('lowongan.id_hrd','=',$hrd->id_hrd)->firstOrFail();
        }
        else {
            $vacancy = Lowongan::join('posisi','lowongan.posisi','=','posisi.id_posisi')->findOrFail($id);
        }

        // Get positions
        if(Auth::user()->role == role_admin()){
            $positions = Posisi::where('id_hrd','=',$vacancy->id_hrd)->orderBy('nama_posisi','asc')->get();
        }
        elseif(Auth::user()->role == role_hrd()){
            $hrd = HRD::where('id_user','=',Auth::user()->id_user)->first();
            $positions = Posisi::where('id_hrd','=',$hrd->id_hrd)->orderBy('nama_posisi','asc')->get();
        }

        // View
        return view('admin/vacancy/edit', [
            'vacancy' => $vacancy,
            'positions' => $positions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        abort(404);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'position' => 'required',
            'status' => 'required',
        ], validationMessages());
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the vacancy
            $vacancy = Lowongan::find($request->id);
            $vacancy->judul_lowongan = $request->name;
            $vacancy->posisi = $request->position;
            $vacancy->status = $request->status;
            $vacancy->save();

            // Redirect
            return redirect()->route('admin.vacancy.index')->with(['message' => 'Berhasil mengupdate data.']);
        }
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
        
        // Get the applicant
        $applicant = Pelamar::find($request->id);

        // Delete the applicant
        $applicant->delete();
        
        // Get the user
        $user = User::find($applicant->id_user);

        // Delete the user
        $user->delete();
        
        // Get the selection
        $selection = Seleksi::where('id_pelamar','=',$request->id)->first();

        // Delete the selection
        if($selection) $selection->delete();

        // Redirect
        return redirect()->route('admin.applicant.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}