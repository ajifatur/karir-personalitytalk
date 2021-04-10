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
              <th width="20"><input type="checkbox"></th>
              <th>Nama</th>
              <th width="100">Username</th>
              <th width="150">Jabatan</th>
              <th width="120">Waktu Daftar</th>
              <th width="150">Perusahaan</th>
              <th width="40">Opsi</th>
            </tr>
          </thead>
        </table>
        <form id="form-delete" class="d-none" method="post" action="/admin/pelamar/delete">
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
    generate_datatable("#dataTable", true, {
      "url": generate_json_url("/admin/pelamar/json{{ strpos(\Request::getRequestUri(), '?') ? '?'.explode('?', \Request::getRequestUri())[1] : '' }}"),
      "columns": [
        {data: 'checkbox', name: 'checkbox'},
        {data: 'name', name: 'name'},
        {data: 'username', name: 'username'},
        {data: 'posisi', name: 'posisi'},
        {data: 'datetime', name: 'datetime'},
        {data: 'company', name: 'company', visible: {{ Auth::user()->role == role_admin() ? 'true' : 'false' }}},
        {data: 'options', name: 'options', orderable: false},
      ],
      "order": [4, 'desc']
    });

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