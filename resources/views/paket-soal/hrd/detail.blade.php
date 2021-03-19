@extends('template/hrd/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Soal Tes {{ $tes->nama_tes }}</h1>
  <p class="mb-4">Soal tes {{ $tes->nama_tes }} yang digunakan untuk mengetes calon karyawan.</p>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Soal Tes {{ $tes->nama_tes }}</h6>
      <div class="dropdown no-arrow">
        <a class="dropdown-toggle btn btn-sm btn-primary" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="More Info">
          <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i> Menu
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
          <div class="dropdown-header">Menu:</div>
          <a class="dropdown-item {{ count($soal) == $paket->jumlah_soal ? 'btn-full' : '' }}" href="/hrd/tes/tipe/{{ $id_tes }}/soal/create/{{ $id_paket }}">Tambah Soal</a>
          <a class="dropdown-item" href="/hrd/tes/tipe/{{ $id_tes }}/soal/export/{{ $id_paket }}">Export ke Excel</a>
          <a class="dropdown-item" href="/hrd/tes/tipe/{{ $id_tes }}/soal/import/{{ $id_paket }}">Import dari Excel</a>
          <a class="dropdown-item" href="/hrd/tes/tipe/{{ $id_tes }}/paket/tutorial/{{ $id_paket }}">Tutorial</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      @if(count($soal) < $paket->jumlah_soal)
      <div class="row">
        <div class="col">
          <div class="alert alert-danger">Jumlah soal yang telah terinput baru <strong>{{ count($soal) }} dari {{ $paket->jumlah_soal }} soal</strong>. Segera lengkapi datanya!</div>
        </div>
      </div>
      @endif
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th width="50">No.</th>
              <th>Pilihan A</th>
              <th>Pilihan B</th>
              <th>Pilihan C</th>
              <th>Pilihan D</th>
              <th width="80">Opsi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($soal as $data)
            <tr>
              <td>{{ $data->nomor }}</td>
              <td><span class="badge badge-dark mr-2 mb-2">{{ $data->soal[0]['disc']['A'] }}</span>{{ $data->soal[0]['pilihan']['A'] }}</td>
              <td><span class="badge badge-dark mr-2 mb-2">{{ $data->soal[0]['disc']['B'] }}</span>{{ $data->soal[0]['pilihan']['B'] }}</td>
              <td><span class="badge badge-dark mr-2 mb-2">{{ $data->soal[0]['disc']['C'] }}</span>{{ $data->soal[0]['pilihan']['C'] }}</td>
              <td><span class="badge badge-dark mr-2 mb-2">{{ $data->soal[0]['disc']['D'] }}</span>{{ $data->soal[0]['pilihan']['D'] }}</td>
              <td>
                <a href="/hrd/tes/tipe/{{ $id_tes }}/soal/edit/{{ $data->id_soal }}" class="btn btn-sm btn-info mr-2 mb-2" data-id="{{ $data->id_soal }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="#" class="btn btn-sm btn-danger mb-2 delete" data-id="{{ $data->id_soal }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
@endsection

@section('css-extra')

<!-- Custom styles for this page -->
<link href="{{ asset('templates/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

<!-- CSS -->
<style type="text/css">
  .badge {width: 2rem; font-size: inherit;}
  td a.btn {width: 36px;}
</style>

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

    // Soal full
    $(document).on("click", ".btn-full", function(e){
      e.preventDefault();
      alert("Sudah tidak bisa menambah soal, karena jumlah soal sudah full!");
    });

    // Delete
    $(document).on("click", ".delete", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      var ask = confirm("Anda yakin ingin menghapus data ini?");
      if(ask){
        $.ajax({
          type: "post",
          url: "/hrd/tes/tipe/{{ $id_tes }}/soal/delete",
          data: {_token: "{{ csrf_token() }}", id: id},
          success: function(response){
            if(response == "Berhasil menghapus data!"){
              alert(response);
              window.location.href = "/hrd/tes/tipe/{{ $id_tes }}/paket/soal/{{ $id_paket }}";
            }
          }
        })
      }
    });
  });
</script>

@endsection