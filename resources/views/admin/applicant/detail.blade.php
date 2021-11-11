@extends('layouts/admin/main')

@section('title', 'Detail Pelamar: '.$applicant->nama_lengkap)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Detail Pelamar</h1>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ asset('assets/images/pas-foto/'.$applicant->pas_foto) }}" class="img-thumbnail" width="200" alt="Foto">
            </div>
            <hr class="my-0">
            <div class="card-body">
                <h5 class="h6 card-title">Info Pelamar</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-1"><i class="bi-calendar me-1"></i> {{ date('d/m/Y', strtotime($applicant->pelamar_at)) }}</li>
                    <li class="mb-1"><i class="bi-clock me-1"></i> {{ date('H:i', strtotime($applicant->pelamar_at)) }} WIB</li>
                    <li class="mb-1"><i class="bi-shuffle me-1"></i> Jabatan <a href="#">{{ $applicant->posisi->nama_posisi }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Profil Pelamar</h5></div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Nama:</span>
                        <span>{{ $applicant->nama_lengkap }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Tempat Lahir:</span>
                        <span>{{ $applicant->tempat_lahir }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Tanggal Lahir:</span>
                        <span>{{ date('d/m/Y', strtotime($applicant->tanggal_lahir)) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Agama:</span>
                        <span>{{ $applicant->nama_agama }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Email:</span>
                        <span>{{ $applicant->email }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Nomor HP:</span>
                        <span>{{ $applicant->nomor_hp }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Nomor Telepon:</span>
                        <span>{{ $applicant->nomor_telepon != '' ? $applicant->nomor_telepon : '-' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>NIK:</span>
                        <span>{{ $applicant->nomor_ktp != '' ? $applicant->nomor_ktp : '-' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Alamat:</span>
                        <span>{{ $applicant->alamat }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Pendidikan Terakhir:</span>
                        <span>{{ $applicant->pendidikan_terakhir }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Riwayat Pekerjaan:</span>
                        <span>{!! $applicant->riwayat_pekerjaan != '' ? nl2br($applicant->riwayat_pekerjaan) : '-' !!}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Status Hubungan:</span>
                        <span>@if($applicant->status_hubungan == 1) Lajang @elseif($applicant->status_hubungan == 2) Menikah @elseif($applicant->status_hubungan == 3) Janda / Duda @endif</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection