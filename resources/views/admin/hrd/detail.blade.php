@extends('layouts/admin/main')

@section('title', 'Detail HRD: '.$hrd->nama_lengkap)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Detail HRD</h1>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ asset('assets/images/pas-foto/'.$hrd->user->foto) }}" class="rounded-circle" height="150" width="150" alt="Foto">
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Profil HRD</h5></div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Nama:</span>
                        <span>{{ $hrd->nama_lengkap }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Tanggal Lahir:</span>
                        <span>{{ date('d/m/Y', strtotime($hrd->tanggal_lahir)) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Email:</span>
                        <span>{{ $hrd->email }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Kode:</span>
                        <span>{{ $hrd->kode }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Nama Perusahaan:</span>
                        <span>{{ $hrd->perusahaan }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Alamat Perusahaan:</span>
                        <span>{{ $hrd->alamat_perusahaan != '' ? $hrd->alamat_perusahaan : '-' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>No. Telepon Perusahaan:</span>
                        <span>{{ $hrd->telepon_perusahaan != '' ? $hrd->telepon_perusahaan : '-' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Akses STIFIn:</span>
                        <span>{{ $hrd->akses_stifin == 1 ? 'Ya' : 'Tidak' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection