@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Kantor</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/kantor">Kantor</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Kantor</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div>
        <a class="btn btn-sm btn-primary" href="/admin/kantor/create">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Tambah Kantor
        </a>
      </div>
      @if(Auth::user()->role == role_admin())
      <div>
        <select id="hrd" class="form-control form-control-sm">
          <option value="0">Semua Perusahaan</option>
          @foreach(get_hrd() as $data)
          <option value="{{ $data->id_hrd }}" {{ isset($_GET) && isset($_GET['hrd']) && $_GET['hrd'] == $data->id_hrd ? 'selected' : '' }}>{{ $data->perusahaan }}</option>
          @endforeach
        </select>
      </div>
      @endif
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
              <th>Kantor</th>
              <th width="80">Karyawan</th>
              @if(Auth::user()->role == role_admin())
              <th width="200">Perusahaan</th>
              @endif
              <th width="60">Opsi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($kantor as $data)
            <tr>
              <td><input type="checkbox"></td>
              <td>
                {{ $data->nama_kantor }}
                <br>
                <small class="text-muted"><i class="fa fa-map-marker mr-2"></i>{{ $data->alamat_kantor != '' ? $data->alamat_kantor : '-' }}
                <br>
                <small class="text-muted"><i class="fa fa-phone mr-2"></i>{{ $data->telepon_kantor != '' ? $data->telepon_kantor : '-' }}
              </td>
              <td>{{ number_format(count_karyawan_by_kantor($data->id_kantor),0,'.','.') }}</td>
              @if(Auth::user()->role == role_admin())
              <td>{{ $data->perusahaan }}<br><small class="text-muted">{{ $data->nama_lengkap }}</small></td>
              @endif
              <td>
                <div class="btn-group">
                  <a href="/admin/kantor/edit/{{ $data->id_kantor }}" class="btn btn-sm btn-warning" data-id="{{ $data->id_kantor }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                  <a href="#" class="btn btn-sm {{ $data->nama_kantor == 'Head Office' ? 'btn-dark not-allowed' : 'btn-danger btn-delete' }}" data-id="{{ $data->id_kantor }}" data-toggle="tooltip" data-placement="top" title="{{ $data->nama_kantor == 'Head Office' ? 'Tidak bisa menghapus kantor Head Office' : 'Hapus' }}" style="{{ $data->nama_kantor == 'Head Office' ? 'cursor: not-allowed;' : '' }}"><i class="fa fa-trash"></i></a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <form id="form-delete" class="d-none" method="post" action="/admin/kantor/delete">
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
  
  // Change HRD
  $(document).on("change", "#hrd", function(){
    var hrd = $(this).val();
    if(hrd == 0) window.location.href = '/admin/kantor';
    else window.location.href = '/admin/kantor?hrd='+hrd;
  });
</script>

@endsection