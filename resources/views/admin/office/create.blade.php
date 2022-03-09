@extends('layouts/admin/main')

@section('title', 'Tambah Kantor')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Tambah Kantor</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.office.store') }}" enctype="multipart/form-data">
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
                    @if(Auth::user()->role->is_global === 1)
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Perusahaan <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="company" class="form-select form-select-sm {{ $errors->has('company') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('company'))
                            <div class="small text-danger">{{ $errors->first('company') }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">No. Telepon</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="phone_number" class="form-control form-control-sm {{ $errors->has('phone_number') ? 'border-danger' : '' }}" value="{{ old('phone_number') }}">
                            @if($errors->has('phone_number'))
                            <div class="small text-danger">{{ $errors->first('phone_number') }}</div>
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
                        <label class="col-lg-2 col-md-3 col-form-label">Status</label>
                        <div class="col-lg-10 col-md-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_main" id="is_main-1" value="1" {{ old('is_main') == "1" ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_main-1">Pusat</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_main" id="is_main-0" value="0" {{ old('is_main') == "0" ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_main-0">Cabang</label>
                            </div>
                            @if($errors->has('is_main'))
                            <div class="small text-danger">{{ $errors->first('is_main') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                            <a href="{{ route('admin.office.index') }}" class="btn btn-sm btn-secondary"><i class="bi-arrow-left me-1"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection