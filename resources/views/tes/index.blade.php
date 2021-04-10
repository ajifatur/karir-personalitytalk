@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Tes</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/tes">Tes</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Tes</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div>
        <a class="btn btn-sm btn-primary" href="/admin/tes/create">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Tambah Tes
        </a>
      </div>
    </div>
    <div class="card-body">
      @if(Session::get('message') != null)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ Session::get('message') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th width="20"><input type="checkbox"></th>
              <th>Tes</th>
              <th width="250">URL</th>
              <th width="60">Opsi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tes as $data)
            <tr>
              <td><input type="checkbox"></td>
              <td>{{ $data->nama_tes }}</td>
              <td><a class="small" href="{{ subdomain_tes() }}tes/{{ $data->path }}" target="_blank">{{ subdomain_tes() }}tes/{{ $data->path }}</a></td>
              <td>
                <div class="btn-group">
                  <a href="/admin/tes/edit/{{ $data->id_tes }}" class="btn btn-sm btn-warning" data-id="{{ $data->id_tes }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                  <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $data->id_tes }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <form id="form-delete" class="d-none" method="post" action="/admin/tes/delete">
            {{ csrf_field() }}
            <input type="hidden" name="id">
        </form>
      </div>
    </div>
  </div>
  
@endsection

@section('js-extra')

<!-- Page level plugins -->
<script src="{{ asset('templates/sb-admin-2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('templates/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- JavaScripts -->
<script type="text/javascript">
  $(document).ready(function() {
    // Call the dataTables jQuery plugin
    generate_datatable("#dataTable");

    // Button Not Allowed
    $(document).on("click", ".not-allowed", function(e){
      e.preventDefault();
    });
  });
</script>

@endsection