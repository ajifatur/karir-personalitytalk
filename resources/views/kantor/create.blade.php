@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Tambah Kantor</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/kantor">Kantor</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tambah Kantor</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <form method="post" action="/admin/kantor/store">
        {{ csrf_field() }}
        <div class="form-group row">
          <label class="col-lg-2 col-md-3 col-form-label">Nama Kantor: <span class="text-danger">*</span></label>
          <div class="col-lg-10 col-md-9">
            <input name="nama_kantor" class="form-control {{ $errors->has('nama_kantor') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama Kantor" value="{{ old('nama_kantor') }}">
            @if($errors->has('nama_kantor'))
            <div class="invalid-feedback">
              {{ ucfirst($errors->first('nama_kantor')) }}
            </div>
            @endif
          </div>
        </div>
        @if(Auth::user()->role == role_admin())
        <div class="form-group row">
          <label class="col-lg-2 col-md-3 col-form-label">Perusahaan: <span class="text-danger">*</span></label>
          <div class="col-lg-10 col-md-9">
            <select name="hrd" class="form-control custom-select {{ $errors->has('hrd') ? 'is-invalid' : '' }}">
                <option value="" disabled selected>--Pilih--</option>
                @foreach($hrd as $data)
                <option value="{{ $data->id_hrd }}" {{ $data->id_hrd == old('hrd') ? 'selected' : '' }}>{{ $data->perusahaan }} ({{ $data->nama_lengkap }})</option>
                @endforeach
            </select>
            @if($errors->has('hrd'))
            <div class="invalid-feedback">
              {{ ucfirst($errors->first('hrd')) }}
            </div>
            @endif
          </div>
        </div>
        @endif
        <div class="form-group row">
          <label class="col-lg-2 col-md-3 col-form-label">Alamat Kantor:</label>
          <div class="col-lg-10 col-md-9">
            <textarea name="alamat_kantor" class="form-control {{ $errors->has('alamat_kantor') ? 'is-invalid' : '' }}" placeholder="Masukkan Alamat Kantor" rows="2">{{ old('alamat_kantor') }}</textarea>
            @if($errors->has('alamat_kantor'))
            <div class="invalid-feedback">
              {{ ucfirst($errors->first('alamat_kantor')) }}
            </div>
            @endif
          </div>
        </div>
        <div class="form-group row">
          <label class="col-lg-2 col-md-3 col-form-label">No. Telepon Kantor:</label>
          <div class="col-lg-10 col-md-9">
            <input name="telepon_kantor" class="form-control {{ $errors->has('telepon_kantor') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor Telepon Kantor" value="{{ old('telepon_kantor') }}">
            @if($errors->has('telepon_kantor'))
            <div class="invalid-feedback">
              {{ ucfirst($errors->first('telepon_kantor')) }}
            </div>
            @endif
          </div>
        </div>
        <div class="form-group row">
          <div class="col-lg-2 col-md-3"></div>
          <div class="col-lg-10 col-md-9">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="/admin/kantor" class="btn btn-secondary">Kembali</a>
          </div>
        </div>
      </form>
    </div>
  </div>

@endsection