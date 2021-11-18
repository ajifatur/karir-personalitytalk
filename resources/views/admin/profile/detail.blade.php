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
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Nama:</span>
                        <span>{{ $user->nama_user }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Tanggal Lahir:</span>
                        <span>{{ date('d/m/Y', strtotime($user->tanggal_lahir)) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Jenis Kelamin:</span>
                        <span>{{ gender($user->jenis_kelamin) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Email:</span>
                        <span>{{ $user->email }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Username:</span>
                        <span>{{ $user->username }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Kunjungan Terakhir:</span>
                        <span>{{ date('d/m/Y, H:i', strtotime($user->last_visit)) }} WIB</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Registrasi:</span>
                        <span>{{ date('d/m/Y, H:i', strtotime($user->created_at)) }} WIB</span>
                    </li>
                    @if($user->role == role('hrd'))
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Nama Perusahaan:</span>
                        <span>{{ $hrd->perusahaan }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Kode Perusahaan:</span>
                        <span>{{ $hrd->kode }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Alamat Perusahaan:</span>
                        <span>{{ $hrd->alamat_perusahaan }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>No. Telp Perusahaan:</span>
                        <span>{{ $hrd->telepon_perusahaan }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Jumlah Kantor:</span>
                        <span>{{ number_format(count_kantor_by_perusahaan($hrd->id_hrd),0,'.','.') }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Jumlah Jabatan:</span>
                        <span>{{ number_format(count_jabatan_by_perusahaan($hrd->id_hrd),0,'.','.') }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Jumlah Karyawan:</span>
                        <span>{{ number_format(count_karyawan_by_perusahaan($hrd->id_hrd),0,'.','.') }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Jumlah Pelamar:</span>
                        <span>{{ number_format(count_pelamar_by_perusahaan($hrd->id_hrd),0,'.','.') }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection