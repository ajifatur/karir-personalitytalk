@extends('layouts/admin/main')

@section('title', 'Detail Karyawan: '.$employee->nama_lengkap)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Detail Karyawan</h1>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ asset('assets/images/pas-foto/'.$employee->user->foto) }}" class="rounded-circle" height="150" width="150" alt="Foto">
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Profil Karyawan</h5></div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nama:</div>
                        <div>{{ $employee->nama_lengkap }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Tempat Lahir:</div>
                        <div>{{ $employee->tempat_lahir }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Tanggal Lahir:</div>
                        <div>{{ date('d/m/Y', strtotime($employee->tanggal_lahir)) }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Agama:</div>
                        <div>{{ $employee->nama_agama }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Email:</div>
                        <div>{{ $employee->email }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nomor HP:</div>
                        <div>{{ $employee->nomor_hp }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nomor Telepon:</div>
                        <div>{{ $employee->nomor_telepon != '' ? $employee->nomor_telepon : '-' }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>NIK:</div>
                        <div>{{ $employee->nik != '' ? $employee->nik : '-' }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Alamat:</div>
                        <div>{{ $employee->alamat }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Pendidikan Terakhir:</div>
                        <div>{{ $employee->pendidikan_terakhir }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection