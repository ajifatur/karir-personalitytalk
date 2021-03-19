@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Ganti Password</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/profil">Profil</a></li>
      <li class="breadcrumb-item active" aria-current="page">Ganti Password</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="row">
    @include('hrd/_sidebar-profil')
    <div class="col-md col-12">
      <div class="card shadow mb-4">
        <div class="card-body">
          @if(Session::get('message') != null)
            <div class="alert {{ Session::get('status') == 1 ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show" role="alert">
              {{ Session::get('message') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
          <form method="post" action="/admin/profil/update-password" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $user->id_user }}">
            <div class="form-group row">
              <label class="col-lg-3 col-md-4 col-form-label">Password Lama: <span class="text-danger">*</span></label>
              <div class="col-lg-9 col-md-8">
                <div class="input-group">
                  <input type="password" name="old_password" class="form-control {{ $errors->has('old_password') ? 'is-invalid' : '' }}" placeholder="Masukkan Password Lama" value="">
                  <div class="input-group-append">
                    <button class="btn {{ $errors->has('old_password') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-toggle-password" type="button"><i class="fa fa-eye"></i></button>
                  </div>
                </div>
                @if($errors->has('old_password'))
                <small class="text-danger">{{ ucfirst($errors->first('old_password')) }}</small>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-3 col-md-4 col-form-label">Password Baru: <span class="text-danger">*</span></label>
              <div class="col-lg-9 col-md-8">
                <div class="input-group">
                  <input type="password" name="new_password" class="form-control {{ $errors->has('new_password') ? 'is-invalid' : '' }}" placeholder="Masukkan Password Baru" value="">
                  <div class="input-group-append">
                    <button class="btn {{ $errors->has('new_password') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-toggle-password" type="button"><i class="fa fa-eye"></i></button>
                  </div>
                </div>
                @if($errors->has('new_password'))
                <small class="text-danger">{{ ucfirst($errors->first('new_password')) }}</small>
                @endif
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-3 col-md-4 col-form-label">Konfirmasi Password Baru: <span class="text-danger">*</span></label>
              <div class="col-lg-9 col-md-8">
                <div class="input-group">
                  <input type="password" name="confirm_password" class="form-control {{ $errors->has('confirm_password') ? 'is-invalid' : '' }}" placeholder="Masukkan Konfirmasi Password" value="">
                  <div class="input-group-append">
                    <button class="btn {{ $errors->has('confirm_password') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-toggle-password" type="button"><i class="fa fa-eye"></i></button>
                  </div>
                </div>
                @if($errors->has('confirm_password'))
                <small class="text-danger">{{ ucfirst($errors->first('confirm_password')) }}</small>
                @endif
              </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-3 col-md-4"></div>
                <div class="col-lg-9 col-md-8">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="/admin/profil" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection