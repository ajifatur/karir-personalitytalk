@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Tambah Jabatan</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/posisi">Jabatan</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tambah Jabatan</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <form method="post" action="/admin/posisi/store">
        {{ csrf_field() }}
        <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Nama Jabatan: <span class="text-danger">*</span></label>
            <div class="col-lg-10 col-md-9">
                <input name="nama_jabatan" class="form-control {{ $errors->has('nama_jabatan') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama Jabatan" value="{{ old('nama_jabatan') }}">
                @if($errors->has('nama_jabatan'))
                <div class="invalid-feedback">
                    {{ ucfirst($errors->first('nama_jabatan')) }}
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
            <label class="col-lg-2 col-md-3 col-form-label">Tes:</label>
            <div class="col-lg-10 col-md-9">
                @foreach($tes as $key=>$data)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tes[]" value="{{ $data->id_tes }}" id="defaultCheck-{{ $key }}">
                        <label class="form-check-label" for="defaultCheck-{{ $key }}">
                        {{ $data->nama_tes }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-2 col-md-3 col-form-label">Keahlian:</label>
            <div class="col-lg-10 col-md-9">
                <div class="row">
                    <div class="col-12 mb-2 input-keahlian" data-id="1">
                        <div class="input-group">
                            <input name="keahlian[]" type="text" class="form-control" placeholder="Masukkan Keahlian">
                            <div class="input-group-append">
                            <button class="btn btn-outline-success btn-add" type="button" data-id="1" title="Tambah"><i class="fa fa-plus"></i></button>
                            <button class="btn btn-outline-danger btn-delete" type="button" data-id="1" title="Hapus"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
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
<script type="text/javascript">
  $(document).ready(function() {
    // Button Add
    $(document).on("click", ".btn-add", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      var input = $(".input-keahlian");
      var html = '';
      html += '<div class="col-12 mb-2 input-keahlian" data-id="' + (input.length+1) + '">';
      html += '<div class="input-group">';
      html += '<input name="keahlian[]" type="text" class="form-control" placeholder="Masukkan Keahlian">';
      html += '<div class="input-group-append">';
      html += '<button class="btn btn-outline-success btn-add" type="button" data-id="' + (input.length+1) + '" title="Tambah"><i class="fa fa-plus"></i></button>';
      html += '<button class="btn btn-outline-danger btn-delete" type="button" data-id="' + (input.length+1) + '" title="Hapus"><i class="fa fa-trash"></i></button>';
      html += '</div>';
      html += '</div>';
      html += '</div>';
      $(".input-keahlian[data-id=" + input.length + "]").after(html);
    });

    // Button Delete
    $(document).on("click", ".btn-delete", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      var input = $(".input-keahlian");
      if(input.length <= 1){
        $(".input-keahlian[data-id=" + id + "]").find("input[type=text]").val("");
      }
      else{
        $(".input-keahlian[data-id=" + id + "]").remove();
        var inputAfter = $(".input-keahlian");
        inputAfter.each(function(key,elem){
          $(elem).attr("data-id", (key+1));
          $(elem).find(".btn-add").attr("data-id", (key+1));
          $(elem).find(".btn-delete").attr("data-id", (key+1));
        });
      }
    });
  });
</script>

@endsection