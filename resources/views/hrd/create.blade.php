@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Tambah HRD</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/hrd">HRD</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tambah HRD</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <form method="post" action="/admin/hrd/store" enctype="multipart/form-data">
        {{ csrf_field() }}
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Nama: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <input name="nama" type="text" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama" value="{{ old('nama') }}">
              @if($errors->has('nama'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('nama')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Email: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <input name="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Masukkan Email" value="{{ old('email') }}">
              @if($errors->has('email'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('email')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Jenis Kelamin: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <select name="jenis_kelamin" class="form-control {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }} custom-select">
                  <option value="" disabled selected>--Pilih--</option>
                  <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                  <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
              </select>
              @if($errors->has('jenis_kelamin'))
              <small class="text-danger">{{ ucfirst($errors->first('jenis_kelamin')) }}</small>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Tanggal Lahir: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <div class="input-group">
                <input name="tanggal_lahir" type="text" class="form-control {{ $errors->has('tanggal_lahir') ? 'is-invalid' : '' }}" placeholder="Masukkan Tanggal Lahir" value="{{ old('tanggal_lahir') }}">
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
            <label class="col-lg-2 col-md-3 col-form-label">Username: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <input name="username" type="text" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" placeholder="Masukkan Username" value="{{ old('username') }}">
              @if($errors->has('username'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('username')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Password: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <div class="input-group">
                <input name="password" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Masukkan Password" value="">
                <div class="input-group-append">
                  <button class="btn {{ $errors->has('password') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-toggle-password show" type="button"><i class="fa fa-eye"></i></button>
                </div>
              </div>
              @if($errors->has('password'))
              <small class="text-danger">{{ ucfirst($errors->first('password')) }}</small>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Kode: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <input name="kode" type="text" class="form-control {{ $errors->has('kode') ? 'is-invalid' : '' }}" placeholder="Masukkan Kode" value="{{ old('kode') }}">
              <small class="text-muted">Kode unik ini digunakan untuk meng-generate akun karyawan dan pelamar.</small>
              @if($errors->has('kode'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('kode')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Perusahaan: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <input name="perusahaan" type="text" class="form-control {{ $errors->has('perusahaan') ? 'is-invalid' : '' }}" placeholder="Masukkan Perusahaan" value="{{ old('perusahaan') }}">
              @if($errors->has('perusahaan'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('perusahaan')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Alamat Perusahaan:</label>
            <div class="col-lg-10 col-md-9">
  		        <textarea name="alamat_perusahaan" class="form-control {{ $errors->has('alamat_perusahaan') ? 'is-invalid' : '' }}" placeholder="Masukkan Alamat Perusahaan" rows="1">{{ old('alamat_perusahaan') }}</textarea>
              @if($errors->has('alamat_perusahaan'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('alamat_perusahaan')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">No. Telepon Perusahaan:</label>
            <div class="col-lg-10 col-md-9">
              <input name="telepon_perusahaan" type="text" class="form-control {{ $errors->has('telepon_perusahaan') ? 'is-invalid' : '' }}" placeholder="Masukkan Nomor Telepon Perusahaan" value="{{ old('telepon_perusahaan') }}">
              @if($errors->has('telepon_perusahaan'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('telepon_perusahaan')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Foto:</label>
            <div class="col-lg-10 col-md-9">
              <button type="button" class="btn btn-sm {{ $errors->has('file') ? 'btn-danger' : 'btn-success' }} btn-upload"><i class="fa fa-upload mr-2"></i> Upload Foto</button>
              <input name="file" id="file" type="file" accept="image/*" class="d-none">
              <input type="hidden" name="foto" value="">
              <br>
              <img class="preview-image img-thumbnail mt-4 d-none" width="300">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Akses Tes:</label>
            <div class="col-lg-10 col-md-9">
              <div class="row">
                @foreach($tes as $key=>$data)
                <div class="col-md-6 col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tes[]" value="{{ $data->id_tes }}" id="defaultCheck-{{ $key }}">
                    <label class="form-check-label" for="defaultCheck-{{ $key }}">
                      {{ $data->nama_tes }}
                    </label>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="form-group row">
              <div class="col-lg-2 col-md-3"></div>
              <div class="col-lg-10 col-md-9">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="/admin/posisi" class="btn btn-secondary">Kembali</a>
              </div>
          </div>
      </form>
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
    
    // Button toggle password
    $(document).on("click", ".btn-toggle-password", function(e){
      e.preventDefault();
      $(this).hasClass("show") ? $("input[name=password]").attr("type","text") : $("input[name=password]").attr("type","password");
      $(this).hasClass("show") ? $(this).find(".fa").removeClass("fa-eye").addClass("fa-eye-slash") : $(this).find(".fa").removeClass("fa-eye-slash").addClass("fa-eye");
      $(this).hasClass("show") ? $(this).removeClass("show").addClass("hide") : $(this).removeClass("hide").addClass("show");
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