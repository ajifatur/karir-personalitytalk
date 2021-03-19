@extends('template/admin/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Tambah Paket Soal {{ $tes->nama_tes }}</h1>
  <p class="mb-4">Paket soal tes {{ $tes->nama_tes }} yang digunakan untuk mengetes calon karyawan.</p>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Tambah Paket Soal {{ $tes->nama_tes }}</h6>
    </div>
    <div class="card-body">
      <form method="post" action="/admin/tes/tipe/{{ $tes->id_tes }}/paket/store">
        {{ csrf_field() }}
        <div class="form-row">
          <div class="form-group col-6">
            <label>Nama Paket Soal:</label>
            <input name="nama_paket" class="form-control {{ $errors->has('nama_paket') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama Paket" value="{{ old('nama_paket') }}">
            @if($errors->has('nama_paket'))
            <div class="invalid-feedback">
              {{ ucfirst($errors->first('nama_paket')) }}
            </div>
            @endif
          </div>
          <div class="form-group col-6">
            <label>Jumlah Soal:</label>
            <input name="jumlah_soal" class="form-control {{ $errors->has('jumlah_soal') ? 'is-invalid' : '' }}" placeholder="Masukkan Jumlah Soal" value="{{ old('jumlah_soal') }}">
            @if($errors->has('jumlah_soal'))
            <div class="invalid-feedback">
              {{ ucfirst($errors->first('jumlah_soal')) }}
            </div>
            @endif
          </div>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="/admin/tes/tipe/{{ $tes->id_tes }}" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>

@endsection

@section('css-extra')

<!-- Custom styles for this page -->
<link href="{{ asset('templates/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

@endsection

@section('js-extra')

<!-- Page level plugins -->
<script src="{{ asset('templates/sb-admin-2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('templates/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- JavaScripts -->
<script type="text/javascript">
  $(document).ready(function() {
    // Call the dataTables jQuery plugin
    $('#dataTable').DataTable();

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>

@endsection