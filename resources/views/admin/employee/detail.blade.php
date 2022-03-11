@extends('layouts/admin/main')

@section('title', 'Detail Karyawan: '.$employee->name)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Detail Karyawan</h1>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ asset('assets/images/pas-foto/'.$employee->avatar) }}" class="rounded-circle" height="150" width="150" alt="Foto">
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
                        <div>{{ $employee->name }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Tempat Lahir:</div>
                        <div>{{ $employee->attribute->birthplace }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Tanggal Lahir:</div>
                        <div>{{ date('d/m/Y', strtotime($employee->attribute->birthdate)) }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Agama:</div>
                        <div>{{ $employee->attribute->religion != null ? religion($employee->attribute->religion) : '' }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Email:</div>
                        <div>{{ $employee->email }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nomor HP:</div>
                        <div>{{ $employee->attribute->phone_number }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>NIK:</div>
                        <div>{{ $employee->attribute->identity_number }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Alamat:</div>
                        <div>{{ $employee->attribute->address }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Pendidikan Terakhir:</div>
                        <div>{{ $employee->attribute->latest_education }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Riwayat Pekerjaan:</div>
                        <div>{{ $employee->attribute->job_experience }}</div>
                    </li>
                    @if($employee->attribute->start_date != null)
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Mulai Bekerja:</div>
                        <div>{{ date('d/m/Y', strtotime($employee->attribute->start_date)) }}</div>
                    </li>
                    @endif
                    <br>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Perusahaan:</div>
                        <div>{{ $employee->attribute->company->name }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Kantor:</div>
                        <div>{{ $employee->attribute->office ? $employee->attribute->office->name : '-' }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Jabatan:</div>
                        <div>{{ $employee->attribute->position ? $employee->attribute->position->name : '-' }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection