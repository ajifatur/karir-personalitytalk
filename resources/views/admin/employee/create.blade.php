@extends('layouts/admin/main')

@section('title', 'Tambah Karyawan')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Tambah Karyawan</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.employee.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="name" class="form-control form-control-sm {{ $errors->has('name') ? 'border-danger' : '' }}" value="{{ old('name') }}" autofocus>
                            @if($errors->has('name'))
                            <div class="small text-danger">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="birthdate" class="form-control form-control-sm {{ $errors->has('birthdate') ? 'border-danger' : '' }}" value="{{ old('birthdate') }}" autocomplete="off">
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
                                <input class="form-check-input" type="radio" name="gender" id="gender-{{ $gender['key'] }}" value="{{ $gender['key'] }}" {{ old('gender') == $gender['key'] ? 'checked' : '' }}>
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
                            <input type="email" name="email" class="form-control form-control-sm {{ $errors->has('email') ? 'border-danger' : '' }}" value="{{ old('email') }}">
                            @if($errors->has('email'))
                            <div class="small text-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">No. HP <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="phone_number" class="form-control form-control-sm {{ $errors->has('phone_number') ? 'border-danger' : '' }}" value="{{ old('phone_number') }}">
                            @if($errors->has('phone_number'))
                            <div class="small text-danger">{{ $errors->first('phone_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">NIK</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="identity_number" class="form-control form-control-sm {{ $errors->has('identity_number') ? 'border-danger' : '' }}" value="{{ old('identity_number') }}">
                            @if($errors->has('identity_number'))
                            <div class="small text-danger">{{ $errors->first('identity_number') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Alamat</label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="address" class="form-control form-control-sm {{ $errors->has('address') ? 'border-danger' : '' }}" rows="3">{{ old('address') }}</textarea>
                            @if($errors->has('address'))
                            <div class="small text-danger">{{ $errors->first('address') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Pendidikan Terakhir</label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="latest_education" class="form-control form-control-sm {{ $errors->has('latest_education') ? 'border-danger' : '' }}" rows="3">{{ old('latest_education') }}</textarea>
                            @if($errors->has('latest_education'))
                            <div class="small text-danger">{{ $errors->first('latest_education') }}</div>
                            @endif
                        </div>
                    </div>
                    @if(Auth::user()->role_id == role('admin'))
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Perusahaan <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="hrd" class="form-select form-select-sm {{ $errors->has('hrd') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($hrds as $hrd)
                                <option value="{{ $hrd->id_hrd }}">{{ $hrd->perusahaan }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('hrd'))
                            <div class="small text-danger">{{ $errors->first('hrd') }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if(Auth::user()->role_id == role('hrd'))
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Kantor <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="office" class="form-select form-select-sm {{ $errors->has('office') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($offices as $office)
                                <option value="{{ $office->id_kantor }}">{{ $office->nama_kantor }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('office'))
                            <div class="small text-danger">{{ $errors->first('office') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Jabatan <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="position" class="form-select form-select-sm {{ $errors->has('position') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($positions as $position)
                                <option value="{{ $position->id_posisi }}">{{ $position->nama_posisi }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('position'))
                            <div class="small text-danger">{{ $errors->first('position') }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Awal Bekerja</label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="start_date" class="form-control form-control-sm {{ $errors->has('start_date') ? 'border-danger' : '' }}" value="{{ old('start_date') }}" autocomplete="off">
                                <span class="input-group-text {{ $errors->has('start_date') ? 'border-danger' : '' }}"><i class="bi-calendar2"></i></span>
                            </div>
                            @if($errors->has('start_date'))
                            <div class="small text-danger">{{ $errors->first('start_date') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Status <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            @foreach(status() as $status)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status-{{ $status['key'] }}" value="{{ $status['key'] }}" {{ old('status') == $status['key'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="status-{{ $status['key'] }}">
                                    {{ $status['name'] }}
                                </label>
                            </div>
                            @endforeach
                            @if($errors->has('status'))
                            <div class="small text-danger">{{ $errors->first('status') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.employee.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
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
    Spandiv.DatePicker("input[name=start_date]");
</script>

@endsection