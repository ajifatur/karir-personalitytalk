@extends('auth.register-layout')

@section('content')

<div class="text-center">
    <h1 class="h4 text-gray-900 mb-5 text-uppercase">Form Data Darurat</h1>
</div>
<form id="form" method="post" action="/lowongan/{{ $url_form }}/daftar/step-4" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="form-group col-sm-6 mb-3">
            <label>Nama Orang Tua: <span class="text-danger">*</span></label>
            <input name="guardian_name" type="text" class="form-control {{ $errors->has('guardian_name') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama" value="{{ !empty($array) ? $array['guardian_name'] : old('guardian_name') }}">
            @if($errors->has('guardian_name'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('guardian_name')) }}</div>
            @endif
        </div>
        <div class="form-group col-sm-6 mb-3">
            <label>No. HP Orang Tua: <span class="text-danger">*</span></label>
            <input name="guardian_phone_number" type="text" class="form-control {{ $errors->has('guardian_phone_number') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor HP" value="{{ !empty($array) ? $array['guardian_phone_number'] : old('guardian_phone_number') }}">
            @if($errors->has('guardian_phone_number'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('guardian_phone_number')) }}</div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-6 mb-3">
            <label>Alamat Orang Tua: <span class="text-danger">*</span></label>
            <textarea name="guardian_address" class="form-control {{ $errors->has('guardian_address') ? 'is-invalid' : '' }}" placeholder="Masukkan Alamat" rows="2">{{ !empty($array) ? $array['guardian_address'] : old('guardian_address') }}</textarea>
            @if($errors->has('guardian_address'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('guardian_address')) }}</div>
            @endif
        </div>
        <div class="form-group col-sm-6 mb-3">
            <label>Pekerjaan Orang Tua: <span class="text-danger">*</span></label>
            <input name="guardian_occupation" type="text" class="form-control {{ $errors->has('guardian_occupation') ? 'is-invalid' : '' }}" placeholder="Masukkan Pekerjaan" value="{{ !empty($array) ? $array['guardian_occupation'] : old('guardian_occupation') }}">
            @if($errors->has('guardian_occupation'))
            <div class="invalid-feedback">{{ ucfirst($errors->first('guardian_occupation')) }}</div>
            @endif
        </div>
    </div>
    <div class="form-group mt-3">
        <div class="row">
            <div class="col-auto ms-auto">
                <input type="hidden" name="url" value="{{ $url_form }}">
                <a href="/lowongan/{{ $url_form }}/daftar/step-3" class="btn btn-secondary">&laquo; Sebelumnya</a>
                <button type="submit" class="btn btn-primary">Selanjutnya &raquo;</button>
            </div>
        </div>
    </div>
</form>

@endsection