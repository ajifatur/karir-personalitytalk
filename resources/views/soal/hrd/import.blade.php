@extends('template/hrd/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Import Soal dari Excel</h1>
  <p class="mb-4">Soal tes {{ $tes->nama_tes }} yang digunakan untuk mengetes calon karyawan.</p>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Import dari Excel</h6>
    </div>
    <div class="card-body">
      <form method="post" action="/hrd/tes/tipe/{{ $tes->id_tes }}/soal/import/post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
          <input type="file" name="file">
          <div>
            @if($errors->has('file'))
              <small class="text-danger">{{ ucfirst($errors->first('file')) }}</small>
            @else
              <small>Hanya bisa mengimport dengan file format .xls dan .xlsx</small>
            @endif
          </div>
        </div>
        <input type="hidden" name="id_paket" value="{{ $id_paket }}">
        <input type="hidden" name="id_tes" value="{{ $tes->id_tes }}">
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>

@endsection