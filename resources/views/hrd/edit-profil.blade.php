@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Edit Profil</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/profil">Profil</a></li>
      <li class="breadcrumb-item active" aria-current="page">Edit Profil</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="row">
    @include('hrd/_sidebar-profil')
    <div class="col-md col-12">
      <div class="card shadow mb-4">
        <div class="card-body">
          @if(Session::get('message') != null)
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ Session::get('message') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif
          <form method="post" action="/admin/profil/update" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $user->id_user }}">
              <div class="form-group row">
                <label class="col-lg-3 col-md-4 col-form-label">Nama: <span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-8">
                  <input name="nama" type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama" value="{{ $user->nama_user }}">
                  @if($errors->has('nama'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('nama')) }}
                  </div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-md-4 col-form-label">Email: <span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-8">
                  <input name="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Masukkan Email" value="{{ $user->email }}">
                  @if($errors->has('email'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('email')) }}
                  </div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-md-4 col-form-label">Jenis Kelamin: <span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-8">
                  <select name="jenis_kelamin" class="form-control {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }} custom-select">
                      <option value="" disabled selected>--Pilih--</option>
                      <option value="L" {{ $user->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                      <option value="P" {{ $user->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                  @if($errors->has('jenis_kelamin'))
                  <small class="text-danger">{{ ucfirst($errors->first('jenis_kelamin')) }}</small>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-md-4 col-form-label">Tanggal Lahir: <span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-8">
                  <div class="input-group">
                    <input name="tanggal_lahir" type="text" class="form-control {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}" placeholder="Masukkan Tanggal Lahir" value="{{ generate_date_format($user->tanggal_lahir, 'd/m/y') }}">
                    <div class="input-group-append">
                      <button class="btn {{ $errors->has('tanggal_lahir') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-toggle-calendar show" type="button"><i class="fa fa-calendar"></i></button>
                    </div>
                  </div>
                  @if($errors->has('tanggal_lahir'))
                  <small class="text-danger">{{ ucfirst($errors->first('tanggal_lahir')) }}</small>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-md-4 col-form-label">Username: <span class="text-danger">*</span></label>
                <div class="col-lg-9 col-md-8">
                  <input name="username" type="text" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" placeholder="Masukkan Username" value="{{ $user->username }}">
                  @if($errors->has('username'))
                  <div class="invalid-feedback">
                    {{ ucfirst($errors->first('username')) }}
                  </div>
                  @endif
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-md-4 col-form-label">Foto:</label>
                <div class="col-lg-9 col-md-8">
                  <button type="button" class="btn btn-sm btn-success btn-upload"><i class="fa fa-upload mr-2"></i> Upload Foto</button>
                  <input name="file" id="file" type="file" accept="image/*" class="d-none">
                  <input type="hidden" name="foto" value="">
                  <br>
                  <img src="{{ asset('assets/images/foto-user/'.$user->foto) }}" class="preview-image img-thumbnail mt-4 {{ $user->foto == '' ? 'd-none' : '' }}" width="175">
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

@section('js-extra')

<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(document).ready(function() {
      // Show calendar
    $('input[name=tanggal_lahir]').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true,
      todayHighlight: true
    });
    $(document).on("click", ".btn-toggle-calendar", function(e){
      e.preventDefault();
      $('input[name=tanggal_lahir]').focus();
    });

    // Button upload
    $(document).on("click", ".btn-upload", function(e){
      e.preventDefault();
      $("#file").trigger("click");
    });

    // Preview photo before upload
    $(document).on("change", "input[type=file]", function(){
      readURL(this);
    });
  });

  function readURL(input) {
    if(input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $(".preview-image").attr('src', e.target.result).removeClass("d-none");
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>

@endsection

@section('css-extra')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />

@endsection