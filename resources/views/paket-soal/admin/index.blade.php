@extends('template/admin/template')

@section('content')

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800">Paket Soal Tes {{ $tes->nama_tes }}</h1>
  <p class="mb-4">Paket soal tes {{ $tes->nama_tes }} yang digunakan untuk mengetes calon karyawan.</p>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Paket Soal Tes {{ $tes->nama_tes }}</h6>
      <a class="btn btn-sm btn-primary" href="/admin/tes/tipe/{{ $tes->id_tes }}/paket/create">
        <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Tambah Paket Soal
      </a>
    </div>
    <div class="card-body">
      @if(count($paket_aktif)<1)
      <div class="row">
        <div class="col">
          <div class="alert alert-danger">Belum ada paket soal yang <strong>aktif</strong>. Segera aktifkan salah satu paket soal!</div>
        </div>
      </div>
      @endif
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th width="50">No.</th>
              <th>Paket Soal</th>
              <th width="50">Jumlah</th>
              <th width="100">Status</th>
              <th width="170">Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1 ?>
            @foreach($paket as $data)
            <tr>
              <td>{{ $i }}</td>
              <td><a href="/admin/tes/tipe/{{ $data->id_tes }}/paket/soal/{{ $data->id_paket }}">{{ $data->nama_paket }}</a></td>
              <td>{{ $data->jumlah_soal }}</td>
              <td>
                <select data-id="{{ $data->id_paket }}" data-value="{{ $data->status }}" class="form-control custom-select status">
                  <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Aktif</option>
                  <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
              </td>
              <td>
                <a href="/admin/tes/tipe/{{ $data->id_tes }}/paket/tutorial/{{ $data->id_paket }}" class="btn btn-sm btn-success mr-2 mb-2" data-id="{{ $data->id_paket }}" data-toggle="tooltip" data-placement="top" title="Tutorial"><i class="fa fa-chalkboard-teacher"></i></a>
                <a href="/admin/tes/tipe/{{ $data->id_tes }}/paket/keterangan/{{ $data->id_paket }}" class="btn btn-sm btn-warning mr-2 mb-2" data-id="{{ $data->id_paket }}" data-toggle="tooltip" data-placement="top" title="Keterangan"><i class="fa fa-info"></i></a>
                <a href="/admin/tes/tipe/{{ $data->id_tes }}/paket/edit/{{ $data->id_paket }}" class="btn btn-sm btn-info mr-2 mb-2" data-id="{{ $data->id_paket }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                @if($data->status == 1)
                <a href="#" class="btn btn-sm btn-dark mb-2 not-allowed" data-id="{{ $data->id_paket }}" data-toggle="tooltip" data-placement="top" title="Tidak bisa menghapus paket soal yang sedang Aktif" style="cursor: not-allowed;"><i class="fa fa-trash"></i></a>
                @else
                <a href="#" class="btn btn-sm btn-danger mb-2 delete" data-id="{{ $data->id_paket }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
                @endif
              </td>
            </tr>
            <?php $i++; ?>
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

    // Button Not Allowed
    $(document).on("click", ".not-allowed", function(e){
      e.preventDefault();
    });

    // Delete
    $(document).on("click", ".delete", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      var ask = confirm("Anda yakin ingin menghapus data ini?");
      if(ask){
        $.ajax({
          type: "post",
          url: "/admin/tes/tipe/{{ $tes->id_tes }}/paket/delete",
          data: {_token: "{{ csrf_token() }}", id: id},
          success: function(response){
            if(response == "Berhasil menghapus data!"){
              alert(response);
              window.location.href = "/admin/tes/tipe/{{ $tes->id_tes }}";
            }
          }
        })
      }
    });

    // Change Status
    $(document).on("change", ".status", function(){
      var status_before = $(this).data("value");
      var id = $(this).data("id");
      var status = $(this).val();
      $(this).find("option[value="+status_before+"]").prop("selected",true);
      var word = status == 1 ? "mengaktifkan" : "menonaktifkan";
      var ask = confirm("Anda yakin ingin "+word+" data ini?");
      if(ask){
        $.ajax({
          type: "post",
          url: "/admin/tes/tipe/{{ $tes->id_tes }}/paket/update-status",
          data: {_token: "{{ csrf_token() }}", id: id, status: status},
          success: function(response){
            if(response == "Berhasil mengupdate status!"){
              alert(response);
              window.location.href = "/admin/tes/tipe/{{ $tes->id_tes }}";
            }
          }
        })
      }
    });
  });
</script>

@endsection