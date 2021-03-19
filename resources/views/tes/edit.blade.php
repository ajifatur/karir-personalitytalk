@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Edit Tes</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/tes">Tes</a></li>
      <li class="breadcrumb-item active" aria-current="page">Edit Tes</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <form id="form" method="post" action="/admin/tes/update">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $tes->id_tes }}">
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Nama Tes: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
              <input name="nama_tes" class="form-control {{ $errors->has('nama_tes') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama Tes" value="{{ $tes->nama_tes }}">
              @if($errors->has('nama_tes'))
              <div class="invalid-feedback">
                {{ ucfirst($errors->first('nama_tes')) }}
              </div>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Tanggal:</label>
            <div class="col-lg-10 col-md-9">
              <div class="input-group">
                <input type="text" id="tanggal" name="tanggal" class="form-control {{ $errors->has('tanggal') ? 'border-danger' : '' }}" value="{{ $tes->waktu_tes != null ? date('d/m/Y', strtotime($tes->waktu_tes)) : '' }}" placeholder="Format: dd/mm/yyyy">
                <div class="input-group-append">
                  <button class="btn {{ $errors->has('tanggal') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-show-datepicker" type="button" data-toggle="tooltip" data-placement="top" title="Atur Tanggal"><i class="fa fa-calendar"></i></button>
                </div>
              </div>
              @if($errors->has('tanggal'))
              <small class="text-danger">{{ ucfirst($errors->first('tanggal')) }}</small>
              @endif
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Jam:</label>
            <div class="col-lg-10 col-md-9">
              <div class="input-group">
                <input type="text" id="jam" name="jam" class="form-control {{ $errors->has('jam') ? 'border-danger' : '' }}" value="{{ $tes->waktu_tes != null ? date('H:i', strtotime($tes->waktu_tes)) : '' }}" placeholder="Format: H:m" data-placement="bottom" data-align="top" data-autoclose="true">
                <div class="input-group-append">
                  <button class="btn {{ $errors->has('jam') ? 'btn-outline-danger' : 'btn-outline-primary' }} btn-show-clockpicker" type="button" data-toggle="tooltip" data-placement="top" title="Atur Jam"><i class="fa fa-clock"></i></button>
                </div>
              </div>
              @if($errors->has('jam'))
              <small class="text-danger">{{ ucfirst($errors->first('jam')) }}</small>
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

@section('css-extra')

<!-- Custom styles for this page -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css" integrity="sha256-lBtf6tZ+SwE/sNMR7JFtCyD44snM3H2FrkB/W400cJA=" crossorigin="anonymous" />

@endsection

@section('js-extra')

<!-- Page level plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js" integrity="sha256-LPgEyZbedErJpp8m+3uasZXzUlSl9yEY4MMCEN9ialU=" crossorigin="anonymous"></script>

<!-- JavaScripts -->
<script type="text/javascript">  
  $(function(){
    $('input[name=tanggal]').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true,
      todayHighlight: true,
    });

    $("input[name=jam]").clockpicker();

    $(document).on("click", ".btn-show-datepicker", function(e){
      e.preventDefault();
      $('input[name=tanggal]').focus();
    });

    $(document).on("click", ".btn-show-clockpicker", function(e){
      e.preventDefault();
      $('input[name=jam]').focus();
    })
  });
</script>

@endsection