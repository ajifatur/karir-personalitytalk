@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Pelamar</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/pelamar">Pelamar</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Pelamar</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div>
        <a class="btn btn-sm btn-primary" href="/admin/pelamar/export{{ isset($_GET) && isset($_GET['hrd']) ? '?hrd='.$_GET['hrd'] : '' }}">
          <i class="fas fa-file-excel fa-sm fa-fw text-gray-400"></i> Ekspor Data
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
              <th width="50">No.</th>
              <th>Nama</th>
              <th width="100">Username</th>
              <th width="100">Posisi</th>
              <th width="120">Tanggal Daftar</th>
              @if(Auth::user()->role == role_admin())
              <th width="200">Perusahaan</th>
              @endif
              <th width="80">Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1 ?>
            @foreach($pelamar as $data)
            <tr>
              <td>{{ $i }}</td>
              <td><a href="/admin/pelamar/detail/{{ $data->id_pelamar }}">{{ ucwords($data->nama_lengkap) }}</a></td>
              <td>{{ $data->id_user->username }}</td>
              <td>{{ $data->posisi != null ? $data->posisi->nama_posisi : '' }}</td>
              <td>
                <span class="d-none">{{ $data->created_at }}</span>
      					{{ date('d/m/Y', strtotime($data->created_at)) }}
                <br>
                <span class="small text-muted">{{ date('H:i', strtotime($data->created_at)) }} WIB</span>
      			  </td>
              @if(Auth::user()->role == role_admin())
              <td>{{ $data->id_hrd->perusahaan }}<br><small class="text-muted">{{ $data->id_hrd->nama_lengkap }}</small></td>
              @endif
              <td>
                <a href="/admin/pelamar/edit/{{ $data->id_pelamar }}" class="btn btn-sm btn-info mr-2 mb-2 {{ $data->id_user->role != 4 ? 'not-allowed' : '' }}" data-id="{{ $data->id_pelamar }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="#" class="btn btn-sm btn-danger mb-2 {{ $data->id_user->role != 4 ? 'not-allowed' : 'btn-delete' }}" data-id="{{ $data->id_pelamar }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
            <?php $i++; ?>
            @endforeach
          </tbody>
        </table>
        <form id="form-delete" class="d-none" method="post" action="/admin/karyawan/delete">
            {{ csrf_field() }}
            <input type="hidden" name="id">
        </form>
      </div>
    </div>
  </div>
  
@endsection

@section('css-extra')

<!-- Custom styles for this page -->
<link href="{{ asset('templates/sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

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
	   alert("Anda tidak diizinkan untuk melakukan aksi pada data ini!");
    });
  });
	
	// Change HRD
	$(document).on("change", "#hrd", function(){
		var hrd = $(this).val();
		if(hrd == 0) window.location.href = '/admin/pelamar';
		else window.location.href = '/admin/pelamar?hrd='+hrd;
	});
</script>

@endsection