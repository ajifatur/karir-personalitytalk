@extends('layouts/admin/main')

@section('title', 'Edit HRD')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Edit HRD</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.hrd.update') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $hrd->id_hrd }}">
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="name" class="form-control form-control-sm {{ $errors->has('name') ? 'border-danger' : '' }}" value="{{ $hrd->nama_lengkap }}" autofocus>
                            @if($errors->has('name'))
                            <div class="small text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="birthdate" class="form-control form-control-sm {{ $errors->has('birthdate') ? 'border-danger' : '' }}" value="{{ date('d/m/Y', strtotime($hrd->tanggal_lahir)) }}" autocomplete="off">
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
                                <input class="form-check-input" type="radio" name="gender" id="gender-{{ $gender['key'] }}" value="{{ $gender['key'] }}" {{ $hrd->jenis_kelamin == $gender['key'] ? 'checked' : '' }}>
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
                        <label class="col-lg-2 col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="email" name="email" class="form-control form-control-sm {{ $errors->has('email') ? 'border-danger' : '' }}" value="{{ $hrd->email }}">
                            @if($errors->has('email'))
                            <div class="small text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Username <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="username" class="form-control form-control-sm {{ $errors->has('username') ? 'border-danger' : '' }}" value="{{ $user->username }}">
                            @if($errors->has('username'))
                            <div class="small text-danger">{{ $errors->first('username') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Password <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group">
                                <input type="password" name="password" class="form-control form-control-sm {{ $errors->has('password') ? 'border-danger' : '' }}">
                                <button type="button" class="btn btn-sm {{ $errors->has('password') ? 'btn-outline-danger' : 'btn-outline-secondary' }} btn-toggle-password"><i class="bi-eye"></i></button>
                            </div>
                            @if($errors->has('password'))
                            <div class="small text-danger">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Kode <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="code" class="form-control form-control-sm {{ $errors->has('code') ? 'border-danger' : '' }}" value="{{ $hrd->kode }}">
                            <div class="small text-muted">Kode unik ini digunakan untuk meng-generate akun karyawan dan pelamar.</div>
                            @if($errors->has('code'))
                            <div class="small text-danger">{{ $errors->first('code') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="company_name" class="form-control form-control-sm {{ $errors->has('company_name') ? 'border-danger' : '' }}" value="{{ $hrd->perusahaan }}">
                            @if($errors->has('company_name'))
                            <div class="small text-danger">{{ $errors->first('company_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">No. Telepon Perusahaan</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="company_phone" class="form-control form-control-sm {{ $errors->has('company_phone') ? 'border-danger' : '' }}" value="{{ $hrd->telepon_perusahaan }}">
                            @if($errors->has('company_phone'))
                            <div class="small text-danger">{{ $errors->first('company_phone') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Alamat Perusahaan</label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="company_address" class="form-control form-control-sm {{ $errors->has('company_address') ? 'border-danger' : '' }}" rows="3">{{ $hrd->alamat_perusahaan }}</textarea>
                            @if($errors->has('company_address'))
                            <div class="small text-danger">{{ $errors->first('company_address') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tes</label>
                        <div class="col-lg-10 col-md-9">
                            <div>
                                @foreach($tests as $test)
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" name="tests[]" type="checkbox" value="{{ $test->id_tes }}" {{ in_array($test->id_tes, $hrd->akses_tes) ? 'checked' : '' }}>
                                    <span class="form-check-label">{{ $test->nama_tes }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Akses STIFIn <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="stifin" id="stifin-1" value="1" {{ $hrd->akses_stifin == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stifin-1">Ya</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="stifin" id="stifin-0" value="0" {{ $hrd->akses_stifin == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="stifin-0">Tidak</label>
                            </div>
                            @if($errors->has('stifin'))
                            <div class="small text-danger">{{ $errors->first('stifin') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.hrd.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
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