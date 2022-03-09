@extends('layouts/admin/main')

@section('title', 'Detail HRD: '.$hrd->name)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Detail HRD</h1>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ asset('assets/images/pas-foto/'.$hrd->avatar) }}" class="rounded-circle" height="150" width="150" alt="Foto">
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Profil HRD</h5></div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nama:</div>
                        <div>{{ $hrd->name }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Tanggal Lahir:</div>
                        <div>{{ date('d/m/Y', strtotime($hrd->attribute->birthdate)) }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Jenis Kelamin:</div>
                        <div>{{ gender($hrd->attribute->gender) }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Email:</div>
                        <div>{{ $hrd->email }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nomor HP:</div>
                        <div>{{ $hrd->attribute->phone_number }}</div>
                    </li>
                    <br>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nama Perusahaan:</div>
                        <div>{{ $hrd->attribute->company->name }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Kode Perusahaan:</div>
                        <div>{{ $hrd->attribute->company->code }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Alamat Perusahaan:</div>
                        <div>{{ $hrd->attribute->company->address }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>No. Telepon Perusahaan:</div>
                        <div>{{ $hrd->attribute->company->phone_number }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Akses STIFIn:</div>
                        <div>{{ $hrd->attribute->company->stifin == 1 ? 'Ya' : 'Tidak' }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection