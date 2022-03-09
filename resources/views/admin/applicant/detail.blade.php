@extends('layouts/admin/main')

@section('title', 'Detail Pelamar: '.$applicant->name)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Detail Pelamar</h1>
    @if(!$selection)
    <a href="#" class="btn btn-sm btn-primary btn-set-test"><i class="bi-clock me-1"></i> Atur Waktu Tes</a>
    @endif
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ asset('assets/images/pas-foto/'.$applicant->photo) }}" class="rounded-circle" height="150" width="150" alt="Foto">
            </div>
            <hr class="my-0">
            <div class="card-body">
                <h5 class="h6 card-title">Info Pelamar</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-1"><i class="bi-calendar me-1"></i> Melamar tanggal {{ date('d/m/Y', strtotime($applicant->created_at)) }}</li>
                    <li class="mb-1"><i class="bi-clock me-1"></i> Melamar pukul {{ date('H:i', strtotime($applicant->created_at)) }} WIB</li>
                    <li class="mb-1"><i class="bi-shuffle me-1"></i> Jabatan <a href="#">{{ $applicant->attribute->position->name }}</a></li>
                </ul>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <h5 class="h6 card-title">Riwayat Pekerjaan</h5>
                <p class="mb-0">{!! $applicant->attribute->job_experience != '' ? nl2br($applicant->attribute->job_experience) : '-' !!}</p>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Profil Pelamar</h5></div>
            <div class="card-body">
                @if(Session::get('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-message">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nama:</div>
                        <div>{{ $applicant->name }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Tempat Lahir:</div>
                        <div>{{ $applicant->attribute->birthplace }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Tanggal Lahir:</div>
                        <div>{{ date('d/m/Y', strtotime($applicant->attribute->birthdate)) }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Agama:</div>
                        <div>{{ religion($applicant->attribute->religion) }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Email:</div>
                        <div>{{ $applicant->email }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Nomor HP:</div>
                        <div>{{ $applicant->attribute->phone_number }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>NIK:</div>
                        <div>{{ $applicant->attribute->identity_number }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Alamat:</div>
                        <div>{{ $applicant->attribute->address }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Pendidikan Terakhir:</div>
                        <div>{{ $applicant->attribute->latest_education }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Status Hubungan:</div>
                        <div>{{ relationship($applicant->attribute->relationship) }}</div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Sosmed:</div>
                        <div>
                            @if($applicant->socmed)
                                {{ $applicant->socmed->account }} ({{ platform($applicant->socmed->platform) }})
                            @endif
                        </div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Pas Foto:</div>
                        <div>
                            @if($applicant->photo != '')
                                <a href="{{ asset('assets/images/pas-foto/'.$applicant->photo) }}" class="btn btn-sm btn-primary" target="_blank"><i class="bi-camera me-1"></i> Lihat Foto</a>
                            @else
                                -
                            @endif
                        </div>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <div>Ijazah:</div>
                        <div>
                            @if($applicant->certificate != '')
                                <a href="{{ asset('assets/images/foto-ijazah/'.$applicant->certificate) }}" class="btn btn-sm btn-primary" target="_blank"><i class="bi-camera me-1"></i> Lihat Foto</a>
                            @else
                                -
                            @endif
                        </div>
                    </li>

                    @if($applicant->data_darurat != null)
                        @foreach($applicant->data_darurat as $key=>$value)
                        <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                            <div>{{ replaceJsonKey($key) }}:</div>
                            <div>{{ $value }}</div>
                        </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-set-test" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Atur Tes</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('admin.selection.store') }}">
                @csrf
                <input type="hidden" name="user_id" value="{{ $applicant->id }}">
                <input type="hidden" name="vacancy_id" value="{{ $applicant->attribute->vacancy_id }}">
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tanggal <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="date" class="form-control form-control-sm {{ $errors->has('date') ? 'border-danger' : '' }}" value="{{ old('date') }}" autocomplete="off">
                                <span class="input-group-text {{ $errors->has('date') ? 'border-danger' : '' }}"><i class="bi-calendar2"></i></span>
                            </div>
                            @if($errors->has('date'))
                            <div class="small text-danger">{{ $errors->first('date') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Jam <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="time" class="form-control form-control-sm {{ $errors->has('time') ? 'border-danger' : '' }}" value="{{ old('time') }}" autocomplete="off">
                                <span class="input-group-text {{ $errors->has('time') ? 'border-danger' : '' }}"><i class="bi-clock"></i></span>
                            </div>
                            @if($errors->has('time'))
                            <div class="small text-danger">{{ $errors->first('time') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-lg-2 col-md-3 col-form-label">Tempat <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="place" class="form-control form-control-sm {{ $errors->has('place') ? 'border-danger' : '' }}" value="{{ old('place') }}">
                            @if($errors->has('place'))
                            <div class="small text-danger">{{ $errors->first('place') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="bi-x-circle me-1"></i> Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    // DatePicker
    Spandiv.DatePicker("input[name=date]");

    // ClockPicker
    Spandiv.ClockPicker("input[name=time]");

    // Button Set Test
    $(document).on("click", ".btn-set-test", function(e) {
        e.preventDefault();
        Spandiv.Modal("#modal-set-test").show();
    });
</script>

@if(count($errors) > 0)
<script type="text/javascript">
    // Show Modal
        Spandiv.Modal("#modal-set-test").show();
</script>
@endif

@endsection