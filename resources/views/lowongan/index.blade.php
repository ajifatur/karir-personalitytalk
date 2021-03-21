@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Lowongan</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/lowongan">Lowongan</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Lowongan</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div>
        <a class="btn btn-sm btn-primary" href="/admin/lowongan/create">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Tambah Lowongan
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
              <th>Lowongan</th>
              <th width="80">Pelamar</th>
              <th width="110">Status</th>
              <th width="120">Waktu Dibuat</th>
              @if(Auth::user()->role == role_admin())
              <th width="150">Perusahaan</th>
              @endif
              <th width="120">Opsi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($lowongan as $data)
            <tr>
              <td><input type="checkbox"></td>
              <td>{{ ucwords($data->judul_lowongan) }}<br><small class="text-muted"><i class="fa fa-route mr-2"></i>{{ ucwords($data->nama_posisi) }}</small></td>
              <td>
                {{ $data->pelamar }} 
                <br>
                <span class="badge badge-info">{{ count_pelamar_belum_diseleksi_by_lowongan($data->id_lowongan) }} belum diseleksi</span>
                <br>
                <span class="badge badge-warning">{{ count_pelamar_belum_dites_by_lowongan($data->id_lowongan) }} belum dites</span>
              </td>
              <td>
                <select data-id="{{ $data->id_lowongan }}" data-value="{{ $data->status }}" class="form-control custom-select status">
                  <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Aktif</option>
                  <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
              </td>
              <td>
      				  <span class="d-none">{{ $data->created_at }}</span>
                {{ date('d/m/Y', strtotime($data->created_at)) }}
                <br>
                <small class="text-muted">{{ date('H:i', strtotime($data->created_at)) }} WIB</span>
      			  </td>
              @if(Auth::user()->role == role_admin())
              <td>{{ $data->perusahaan }}<br><small class="text-muted">{{ $data->nama_lengkap }}</small></td>
              @endif
              <td>
                <div class="btn-group">
                  <span data-toggle="tooltip" data-placement="bottom" title="Lihat URL">
                    <a href="#" class="btn btn-sm btn-success show-url" data-id="{{ $data->id_lowongan }}" data-url="{{ $data->url_lowongan }}"  data-toggle="modal" data-target="#showURLModal"><i class="fa fa-link"></i></a>
                  </span>
                  <a href="/admin/lowongan/pelamar/{{ $data->id_lowongan }}" class="btn btn-sm btn-info" data-id="{{ $data->id_lowongan }}" data-toggle="tooltip" data-placement="top" title="Lihat Pelamar"><i class="fa fa-user-tie"></i></a>
                  <a href="/admin/lowongan/edit/{{ $data->id_lowongan }}" class="btn btn-sm btn-warning" data-id="{{ $data->id_lowongan }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                  <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $data->id_lowongan }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <form id="form-delete" class="d-none" method="post" action="/admin/lowongan/delete">
            {{ csrf_field() }}
            <input type="hidden" name="id">
        </form>
      </div>
    </div>
  </div>

  <!-- Show URL Modal-->
  <div class="modal fade" id="showURLModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">URL Formulir</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Berikut adalah URL yang digunakan untuk menuju ke formulir pendaftaran lowongan:</label>
          <div class="input-group">
            <input type="text" id="url" class="form-control" value="{{ url('/') }}" readonly>
            <div class="input-group-append">
              <button class="btn btn-outline-primary btn-copy" type="button" data-toggle="tooltip" data-placement="top" title="Copy to Clipboard"><i class="fa fa-copy"></i></button>
              <button class="btn btn-outline-primary btn-link" type="button" data-toggle="tooltip" data-placement="top" title="Kunjungi URL"><i class="fa fa-link"></i></button>
            </div>
          </div>
          <input type="hidden" id="url-root" value="{{ url('/') }}">
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
        </div>
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

    // Show URL
    $(document).on("click", ".show-url", function(e){
      e.preventDefault();
      var id = $(this).data("id");
      var url = $(this).data("url");
      var url_root = $("#url-root").val();
      $("#url").val(url_root + '/lowongan/' + url);
      $(".btn-link").attr('data-url', url_root + '/lowongan/' + url);
    })

    // Copy to Clipboard
    $(document).on("click", ".btn-copy", function(e){
      e.preventDefault();
      var url = $(this).data("url");
      document.getElementById("url").select();
      document.getElementById("url").setSelectionRange(0, 99999);
      document.execCommand("copy");
      $(this).attr('data-original-title','Copied!');
      $(this).tooltip("show");
      $(this).attr('data-original-title','Copy to Clipboard');
    })

    // Visit URL
    $(document).on("click", ".btn-link", function(e){
      e.preventDefault();
      var url = $(this).data("url");
      window.open(url, '_blank');
    })

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
          url: "/admin/lowongan/update-status",
          data: {_token: "{{ csrf_token() }}", id: id, status: status},
          success: function(response){
            if(response == "Berhasil mengupdate status!"){
              alert(response);
              window.location.href = "/admin/lowongan";
            }
          }
        })
      }
    });
  });
	
	// Change HRD
	$(document).on("change", "#hrd", function(){
		var hrd = $(this).val();
		if(hrd == 0) window.location.href = '/admin/lowongan';
		else window.location.href = '/admin/lowongan?hrd='+hrd;
	});
</script>

@endsection