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
                @if(Session::get('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-message">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="identity-tab" data-bs-toggle="tab" data-bs-target="#identity" type="button" role="tab" aria-controls="identity" aria-selected="true">Identitas</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="attachment-tab" data-bs-toggle="tab" data-bs-target="#attachment" type="button" role="tab" aria-controls="attachment" aria-selected="false">Lampiran</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="socmed-tab" data-bs-toggle="tab" data-bs-target="#socmed" type="button" role="tab" aria-controls="socmed" aria-selected="false">Media Sosial</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="guardian-tab" data-bs-toggle="tab" data-bs-target="#guardian" type="button" role="tab" aria-controls="guardian" aria-selected="false">Data Darurat</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="skill-tab" data-bs-toggle="tab" data-bs-target="#skill" type="button" role="tab" aria-controls="skill" aria-selected="false">Keahlian</button>
                    </li>
                </ul>
                <div class="tab-content p-2" id="myTabContent">
                    <div class="tab-pane fade show active" id="identity" role="tabpanel" aria-labelledby="identity-tab">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Nama:</div>
                                <div>{{ $employee->name }}</div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Tempat Lahir:</div>
                                <div>{{ $employee->attribute->birthplace }}</div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Tanggal Lahir:</div>
                                <div>{{ date('d/m/Y', strtotime($employee->attribute->birthdate)) }}</div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Agama:</div>
                                <div>{{ religion($employee->attribute->religion) }}</div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Email:</div>
                                <div>{{ $employee->email }}</div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Nomor HP:</div>
                                <div>{{ $employee->attribute->phone_number }}</div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">NIK:</div>
                                <div>{{ $employee->attribute->identity_number }}</div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Alamat:</div>
                                <div>{{ $employee->attribute->address }}</div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Status Hubungan:</div>
                                <div>{{ relationship($employee->attribute->relationship) }}</div>
                            </li>
                            @if($employee->attribute->start_date != null)
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Mulai Bekerja:</div>
                                <div>{{ date('d/m/Y', strtotime($employee->attribute->start_date)) }}</div>
                            </li>
                            @endif
                            <br>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Perusahaan:</div>
                                <div>{{ $employee->attribute->company->name }}</div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Kantor:</div>
                                <div>{{ $employee->attribute->office ? $employee->attribute->office->name : '-' }}</div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Jabatan:</div>
                                <div>{{ $employee->attribute->position ? $employee->attribute->position->name : '-' }}</div>
                            </li>
                        </ul>
                        <br>
                        <p class="fw-bold text-dark mb-0">Pendidikan Terakhir:</p>
                        <p class="mb-0">{{ $employee->attribute->latest_education }}</p>
                        <hr class="my-1">
                        <p class="fw-bold text-dark mb-0">Riwayat Pekerjaan:</p>
                        <p class="mb-0">{!! $employee->attribute->job_experience != '' ? nl2br($employee->attribute->job_experience) : '-' !!}</p>
                    </div>
                    <div class="tab-pane fade" id="attachment" role="tabpanel" aria-labelledby="attachment-tab">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Pas Foto:</div>
                                <div>
                                    @if($employee->photo != '')
                                        <a href="{{ asset('assets/images/pas-foto/'.$employee->photo) }}" class="btn btn-sm btn-primary" target="_blank"><i class="bi-camera me-1"></i> Lihat Foto</a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </li>
                            <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                <div class="fw-bold">Ijazah:</div>
                                <div>
                                    @if($employee->certificate != '')
                                        <a href="{{ asset('assets/images/foto-ijazah/'.$employee->certificate) }}" class="btn btn-sm btn-primary" target="_blank"><i class="bi-camera me-1"></i> Lihat Foto</a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="socmed" role="tabpanel" aria-labelledby="socmed-tab">
                        @if($employee->socmed)
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                    <div class="fw-bold">{{ platform($employee->socmed->platform) }}:</div>
                                    <div>{{ $employee->socmed->account }}</div>
                                </li>
                            </ul>
                        @else
                            <div class="alert alert-danger" role="alert">
                                <div class="alert-message">Tidak ada data.</div>
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="guardian" role="tabpanel" aria-labelledby="guardian-tab">
                        @if($employee->guardian)
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                    <div class="fw-bold">Nama Orang Tua / Wali:</div>
                                    <div>{{ $employee->guardian->name }}</div>
                                </li>
                                <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                    <div class="fw-bold">Nomor HP Orang Tua / Wali:</div>
                                    <div>{{ $employee->guardian->phone_number }}</div>
                                </li>
                                <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                    <div class="fw-bold">Alamat Orang Tua / Wali:</div>
                                    <div>{{ $employee->guardian->address }}</div>
                                </li>
                                <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                    <div class="fw-bold">Pekerjaan Orang Tua / Wali:</div>
                                    <div>{{ $employee->guardian->occupation }}</div>
                                </li>
                            </ul>
                        @else
                            <div class="alert alert-danger" role="alert">
                                <div class="alert-message">Tidak ada data.</div>
                            </div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="skill" role="tabpanel" aria-labelledby="skill-tab">
                        @if(count($employee->skills) > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($employee->skills as $skill)
                                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                                        <div class="fw-bold">{{ $skill->skill->name }}:</div>
                                        <div>
                                            {{ $skill->score }}
                                            @php
                                                switch($skill->score) {
                                                    case 3: echo '(Baik)'; break;
                                                    case 2: echo '(Cukup)'; break;
                                                    case 1: echo '(Kurang)'; break;
                                                }
                                            @endphp
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-danger" role="alert">
                                <div class="alert-message">Tidak ada data.</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection