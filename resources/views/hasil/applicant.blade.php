@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Tes Pelamar</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/hasil">Hasil Tes</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Tes Pelamar</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div>
      </div>
      <div>
        <form class="form-inline">
          <select id="tes" class="form-control form-control-sm">
            <option value="0">Semua Tes</option>
            @foreach(get_data_tes() as $data)
            <option value="{{ $data->id_tes }}" {{ isset($_GET) && isset($_GET['tes']) && $_GET['tes'] == $data->id_tes ? 'selected' : '' }}>{{ $data->nama_tes }}</option>
            @endforeach
          </select>
          @if(Auth::user()->role == role_admin())
          <select id="hrd" class="form-control form-control-sm">
            <option value="0">Semua Perusahaan</option>
            @foreach(get_hrd() as $data)
            <option value="{{ $data->id_hrd }}" {{ isset($_GET) && isset($_GET['hrd']) && $_GET['hrd'] == $data->id_hrd ? 'selected' : '' }}>{{ $data->perusahaan }}</option>
            @endforeach
          </select>
          @endif
        </form>
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
              <th>Nama</th>
              <th width="150">Jabatan</th>
              <th width="150">Perusahaan</th>
              <th width="100">Tes</th>
              <th width="100">Waktu Tes</th>
              <th width="40">Opsi</th>
            </tr>
          </thead>
        </table>
        <form id="form-delete" class="d-none" method="post" action="/admin/hasil/delete">
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
      "url": generate_json_url("/admin/hasil/json/pelamar{{ strpos(\Request::getRequestUri(), '?') ? '?'.explode('?', \Request::getRequestUri())[1] : '' }}"),
      "columns": [
        {data: 'checkbox', name: 'checkbox'},
        {data: 'name', name: 'name'},
        {data: 'posisi', name: 'posisi'},
        {data: 'company', name: 'company', visible: {{ Auth::user()->role == role_admin() ? 'true' : 'false' }}},
        {data: 'nama_tes', name: 'nama_tes'},
        {data: 'datetime', name: 'datetime'},
        {data: 'options', name: 'options', orderable: false},
      ],
      "order": [5, 'desc']
    });

    // Button Not Allowed
    $(document).on("click", ".not-allowed", function(e){
      e.preventDefault();
    });
  });
  
  // Change HRD
  $(document).on("change", "#tes, #hrd", function(){
    var tes = $("#tes").val();
    var hrd = $("#hrd").length == 1 ? $("#hrd").val() : null;

    if(hrd != null){
      if(tes == 0 && hrd == 0) window.location.href = '/admin/hasil/pelamar';
      else window.location.href = '/admin/hasil/pelamar?tes='+tes+'&hrd='+hrd;
    }
    else{
      if(tes == 0) window.location.href = '/admin/hasil/pelamar';
      else window.location.href = '/admin/hasil/pelamar?tes='+tes;
    }
  });
</script>

@endsection