@extends('layouts/admin/main')

@section('title', 'Edit Pelamar')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit Pelamar</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.applicant.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $applicant->id_pelamar }}">
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="name" class="form-control form-control-sm {{ $errors->has('name') ? 'border-danger' : '' }}" value="{{ $applicant->nama_lengkap }}" autofocus>
                            @if($errors->has('name'))
                            <div class="small text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="birthplace" class="form-control form-control-sm {{ $errors->has('birthplace') ? 'border-danger' : '' }}" value="{{ $applicant->tempat_lahir }}">
                            @if($errors->has('birthplace'))
                            <div class="small text-danger">{{ $errors->first('birthplace') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="birthdate" class="form-control form-control-sm {{ $errors->has('birthdate') ? 'border-danger' : '' }}" value="{{ date('d/m/Y', strtotime($applicant->tanggal_lahir)) }}" autocomplete="off">
                                <span class="input-group-text {{ $errors->has('birthdate') ? 'border-danger' : '' }}"><i class="bi-calendar2"></i></span>
                            </div>
                            @if($errors->has('birthdate'))
                            <div class="small text-danger">{{ $errors->first('birthdate') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            @foreach(gender() as $gender)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="gender-{{ $gender['key'] }}" value="{{ $gender['key'] }}" {{ $applicant->jenis_kelamin == $gender['key'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender-{{ $gender['key'] }}">
                                    {{ $gender['name'] }}
                                </label>
                            </div>
                            @endforeach
                            @if($errors->has('gender'))
                            <div class="small text-danger">{{ $errors->first('gender') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Agama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="religion" class="form-select form-select-sm {{ $errors->has('religion') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach(religion() as $religion)
                                <option value="{{ $religion['key'] }}" {{ $applicant->agama == $religion['key'] ? 'selected' : '' }}>{{ $religion['name'] }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('religion'))
                            <div class="small text-danger">{{ $errors->first('religion') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="email" name="email" class="form-control form-control-sm {{ $errors->has('email') ? 'border-danger' : '' }}" value="{{ $applicant->email }}">
                            @if($errors->has('email'))
                            <div class="small text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">No. HP <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="phone_number" class="form-control form-control-sm {{ $errors->has('phone_number') ? 'border-danger' : '' }}" value="{{ $applicant->nomor_hp }}">
                            @if($errors->has('phone_number'))
                            <div class="small text-danger">{{ $errors->first('phone_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">NIK</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="identity_number" class="form-control form-control-sm {{ $errors->has('identity_number') ? 'border-danger' : '' }}" value="{{ $applicant->nomor_ktp }}">
                            @if($errors->has('identity_number'))
                            <div class="small text-danger">{{ $errors->first('identity_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Alamat <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="address" class="form-control form-control-sm {{ $errors->has('address') ? 'border-danger' : '' }}" rows="3">{{ $applicant->alamat }}</textarea>
                            @if($errors->has('address'))
                            <div class="small text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Pendidikan Terakhir</label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="latest_education" class="form-control form-control-sm {{ $errors->has('latest_education') ? 'border-danger' : '' }}" rows="3">{{ $applicant->pendidikan_terakhir }}</textarea>
                            @if($errors->has('latest_education'))
                            <div class="small text-danger">{{ $errors->first('latest_education') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Riwayat Pekerjaan</label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="job_experience" class="form-control form-control-sm {{ $errors->has('job_experience') ? 'border-danger' : '' }}" rows="3">{{ $applicant->riwayat_pekerjaan }}</textarea>
                            @if($errors->has('job_experience'))
                            <div class="small text-danger">{{ $errors->first('job_experience') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Sosial Media <span class="text-danger">*</span></label>
                        <div class="col-lg-4 col-md-5">
                            <div class="input-group input-group-sm">
                                <select name="platform" class="form-select form-select-sm {{ $errors->has('platform') ? 'border-danger' : '' }}">
                                    @foreach(platform() as $platform)
                                    <option value="{{ $platform['name'] }}" {{ array_keys($applicant->akun_sosmed)[0] == $platform['name'] ? 'selected' : '' }}>{{ $platform['name'] }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="socmed" class="form-control form-control-sm {{ $errors->has('socmed') ? 'border-danger' : '' }}" value="{{ $applicant->akun_sosmed[array_keys($applicant->akun_sosmed)[0]] }}">
                            </div>
                            @if($errors->has('socmed'))
                            <div class="small text-danger">{{ $errors->first('socmed') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama Orang Tua <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="guardian_name" class="form-control form-control-sm {{ $errors->has('guardian_name') ? 'border-danger' : '' }}" value="{{ $applicant->data_darurat['nama_orang_tua'] }}">
                            @if($errors->has('guardian_name'))
                            <div class="small text-danger">{{ $errors->first('guardian_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Alamat Orang Tua <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="guardian_address" class="form-control form-control-sm {{ $errors->has('guardian_address') ? 'border-danger' : '' }}" rows="3">{{ $applicant->data_darurat['alamat_orang_tua'] }}</textarea>
                            @if($errors->has('guardian_address'))
                            <div class="small text-danger">{{ $errors->first('guardian_address') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">No. HP Orang Tua <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="guardian_phone_number" class="form-control form-control-sm {{ $errors->has('guardian_phone_number') ? 'border-danger' : '' }}" value="{{ $applicant->data_darurat['nomor_hp_orang_tua'] }}">
                            @if($errors->has('guardian_phone_number'))
                            <div class="small text-danger">{{ $errors->first('guardian_phone_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Pekerjaan Orang Tua <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="guardian_occupation" class="form-control form-control-sm {{ $errors->has('guardian_occupation') ? 'border-danger' : '' }}" value="{{ $applicant->data_darurat['pekerjaan_orang_tua'] }}">
                            @if($errors->has('guardian_occupation'))
                            <div class="small text-danger">{{ $errors->first('guardian_occupation') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.applicant.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    // Datepicker
    Spandiv.DatePicker("input[name=birthdate]");
</script>

@endsection