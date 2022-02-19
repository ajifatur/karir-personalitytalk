@extends('layouts/admin/main')

@section('title', 'Tambah STIFIn')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Tambah STIFIn</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.stifin.store') }}" enctype="multipart/form-data">
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
                        <label class="col-lg-2 col-md-3 col-form-label">Tanggal Lahir</label>
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
                    @if(Auth::user()->role->is_global === 1)
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Perusahaan <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="hrd" class="form-select form-select-sm {{ $errors->has('hrd') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($hrds as $hrd)
                                <option value="{{ $hrd->id_hrd }}" {{ old('hrd') == $hrd->id_hrd ? 'selected' : '' }}>{{ $hrd->perusahaan }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('hrd'))
                            <div class="small text-danger">{{ $errors->first('hrd') }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                    <hr>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tipe <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="type" class="form-select form-select-sm {{ $errors->has('type') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($types as $type)
                                <option value="{{ $type->id_st }}" {{ old('type') == $type->id_st ? 'selected' : '' }}>{{ $type->test_name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                            <div class="small text-danger">{{ $errors->first('type') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tanggal Tes</label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="test_at" class="form-control form-control-sm {{ $errors->has('test_at') ? 'border-danger' : '' }}" value="{{ old('test_at') }}" autocomplete="off">
                                <span class="input-group-text {{ $errors->has('test_at') ? 'border-danger' : '' }}"><i class="bi-calendar2"></i></span>
                            </div>
                            @if($errors->has('test_at'))
                            <div class="small text-danger">{{ $errors->first('test_at') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tujuan Tes <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="aim" class="form-select form-select-sm {{ $errors->has('aim') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($aims as $aim)
                                <option value="{{ $aim->id_sa }}" {{ old('aim') == $aim->id_sa ? 'selected' : '' }}>{{ $aim->aim }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('aim'))
                            <div class="small text-danger">{{ $errors->first('aim') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.stifin.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
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
    Spandiv.DatePicker("input[name=test_at]");
</script>

@endsection