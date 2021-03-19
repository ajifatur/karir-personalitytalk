@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Edit Lowongan</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/lowongan">Lowongan</a></li>
      <li class="breadcrumb-item active" aria-current="page">Edit Lowongan</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <form method="post" action="/admin/lowongan/update">
        {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $lowongan->id_lowongan }}">
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Judul: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <input type="text" name="judul" class="form-control {{ $errors->has('judul') ? 'is-invalid' : '' }}" placeholder="Masukkan Judul Lowongan" value="{{ $lowongan->judul_lowongan }}">
              @if($errors->has('judul'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('judul')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Jabatan: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <select name="jabatan" class="form-control custom-select {{ $errors->has('jabatan') ? 'is-invalid' : '' }}">
                  <option value="" disabled selected>--Pilih--</option>
                  @foreach($posisi as $data)
                  <option value="{{ $data->id_posisi }}" {{ $data->id_posisi == $lowongan->posisi ? 'selected' : '' }}>{{ $data->nama_posisi }} {{ Auth::user()->role == role_admin() ? '('.get_perusahaan_name($data->id_hrd).')' : '' }}</option>
                  @endforeach
              </select>
              @if($errors->has('jabatan'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('jabatan')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Status: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <div class="custom-control custom-radio">
                <input type="radio" id="customRadioInline1" name="status" class="custom-control-input" value="1" {{ $lowongan->status === 1 ? 'checked' : '' }}>
                <label class="custom-control-label" for="customRadioInline1">Aktif</label>
              </div>
              <div class="custom-control custom-radio">
                <input type="radio" id="customRadioInline2" name="status" class="custom-control-input" value="0" {{ $lowongan->status === 0 ? 'checked' : '' }}>
                <label class="custom-control-label" for="customRadioInline2">Tidak Aktif</label>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-2 col-md-3"></div>
            <div class="col-lg-10 col-md-9">
              <button type="submit" class="btn btn-primary">Submit</button>
              <a href="/admin/lowongan" class="btn btn-secondary">Kembali</a>
            </div>
          </div>
      </form>
    </div>
  </div>

@endsection

@section('css-extra')

<!-- Custom styles for this page -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/ui/trumbowyg.min.css"> -->

<!-- CSS -->
<style type="text/css">
  .trumbowyg-box {margin-top: 0;}
</style>

@endsection

@section('js-extra')

<!-- Page level plugins -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/trumbowyg.min.js"></script> -->

<!-- JavaScripts -->
<script type="text/javascript">
  // $(function(){
  // // Trumbowyg
  // $('textarea').trumbowyg();
  // })
</script>

@if($errors->has('deskripsi_lowongan'))
<script type="text/javascript">
  $(function(){
    $('.trumbowyg-box').addClass('border-danger');
  })
</script>
@endif

@endsection