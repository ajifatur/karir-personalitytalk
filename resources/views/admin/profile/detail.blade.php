@extends('layouts/admin/main')

@section('title', 'Profil')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Profil</h1>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ Auth::user()->foto != '' ? asset('assets/images/foto-user/'.Auth::user()->foto) : asset('assets/images/default/user.png') }}" class="rounded-circle" height="150" width="150" alt="Foto">
            </div>
            <hr class="my-0">
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('admin.profile') }}" class="list-group-item list-group-item-action py-2 px-3 {{ is_int(strpos(Request::url(), route('admin.profile'))) ? 'active' : '' }}">Profil</a>
                    <a href="{{ route('admin.profile.edit') }}" class="list-group-item list-group-item-action py-2 px-3 {{ is_int(strpos(Request::url(), route('admin.profile.edit'))) && !is_int(strpos(Request::url(), route('admin.profile.edit-password'))) ? 'active' : '' }}">Edit Profil</a>
                    <a href="{{ route('admin.profile.edit-password') }}" class="list-group-item list-group-item-action py-2 px-3 {{ is_int(strpos(Request::url(), route('admin.profile.edit-password'))) ? 'active' : '' }}">Edit Password</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Profil Pengguna</h5></div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nama:</div>
                        <div>{{ $user->nama_user }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Tanggal Lahir:</div>
                        <div>{{ date('d/m/Y', strtotime($user->tanggal_lahir)) }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Jenis Kelamin:</div>
                        <div>{{ gender($user->jenis_kelamin) }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Email:</div>
                        <div>{{ $user->email }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Username:</div>
                        <div>{{ $user->username }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Kunjungan Terakhir:</div>
                        <div>{{ date('d/m/Y, H:i', strtotime($user->last_visit)) }} WIB</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Registrasi:</div>
                        <div>{{ date('d/m/Y, H:i', strtotime($user->created_at)) }} WIB</div>
                    </li>
                    @if($user->role == role('hrd'))
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nama Perusahaan:</div>
                        <div>{{ $hrd->perusahaan }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Kode Perusahaan:</div>
                        <div>{{ $hrd->kode }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Alamat Perusahaan:</div>
                        <div>{{ $hrd->alamat_perusahaan }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>No. Telp Perusahaan:</div>
                        <div>{{ $hrd->telepon_perusahaan }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Jumlah Kantor:</div>
                        <div>{{ number_format(count_kantor_by_perusahaan($hrd->id_hrd),0,'.','.') }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Jumlah Jabatan:</div>
                        <div>{{ number_format(count_jabatan_by_perusahaan($hrd->id_hrd),0,'.','.') }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Jumlah Karyawan:</div>
                        <div>{{ number_format(count_karyawan_by_perusahaan($hrd->id_hrd),0,'.','.') }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Jumlah Pelamar:</div>
                        <div>{{ number_format(count_pelamar_by_perusahaan($hrd->id_hrd),0,'.','.') }}</div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection