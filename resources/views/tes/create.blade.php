@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Tambah Tes</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/tes">Tes</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tambah Tes</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <form id="form" method="post" action="/admin/tes/store">
        {{ csrf_field() }}
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Nama Tes: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <input name="nama_tes" class="form-control {{ $errors->has('nama_tes') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama Tes" value="{{ old('nama_tes') }}">
              @if($errors->has('nama_tes'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('nama_tes')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-2 col-md-3"></div>
            <div class="col-lg-10 col-md-9">
              <button type="submit" class="btn btn-primary">Submit</button>
              <a href="/admin/tes" class="btn btn-secondary">Kembali</a>
            </div>
          </div>
      </form>
    </div>
  </div>

@endsection