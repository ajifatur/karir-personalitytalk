@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data HRD</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/hrd">HRD</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data HRD</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div>
        <a class="btn btn-sm btn-primary" href="/admin/hrd/create">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Tambah HRD
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
              <th>Identitas</th>
              <th width="100">Username</th>
              <th width="150">Perusahaan</th>
              <th width="100">Last Visit</th>
              <th width="60">Opsi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($hrd as $data)
            <tr>
              <td><input type="checkbox"></td>
              <td>{{ $data->nama_user }}<br><small class="text-muted">{{ $data->email }}</small></td>
              <td>{{ $data->username }}</td>
              <td>{{ $data->perusahaan }}</td>
              <td>
                <span class="d-none">{{ $data->last_visit }}</span>
                {{ $data->last_visit != '' ? date('d/m/Y', strtotime($data->last_visit)) : '-' }}
                <br>
                <small class="text-muted">{{ $data->last_visit != '' ? date('H:i', strtotime($data->last_visit)).' WIB' : '' }}</small>
              </td>
              <td>
                <div class="btn-group">
                  <a href="/admin/hrd/edit/{{ $data->id_user }}" class="btn btn-sm btn-warning" data-id="{{ $data->id_user }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                  @if($data->id_user == Auth::user()->id_user)
                  <a href="#" class="btn btn-sm btn-dark not-allowed" data-id="{{ $data->id_user }}" data-toggle="tooltip" data-placement="top" title="Tidak bisa menghapus akun sendiri" style="cursor: not-allowed;"><i class="fa fa-trash"></i></a>
                  @else
                  <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $data->id_user }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <form id="form-delete" class="d-none" method="post" action="/admin/hrd/delete">
            {{ csrf_field() }}
            <input type="hidden" name="id">
        </form>
      </div>
    </div>
  </div>
  
@endsection

@section('js-extra')

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