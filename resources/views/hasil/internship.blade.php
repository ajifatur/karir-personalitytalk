@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Tes Magang</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/hasil">Hasil Tes</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Tes Magang</li>
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
<!--           <select id="hrd" class="form-control form-control-sm">
            <option value="0">Semua Perusahaan</option>
            @foreach(get_hrd() as $data)
            <option value="{{ $data->id_hrd }}" {{ isset($_GET) && isset($_GET['hrd']) && $_GET['hrd'] == $data->id_hrd ? 'selected' : '' }}>{{ $data->perusahaan }}</option>
            @endforeach
          </select> -->
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
              <th width="50">No.</th>
              <th>Nama</th>
              <th width="150">Posisi</th>
              @if(Auth::user()->role == role_admin())
              <!-- <th width="200">Perusahaan</th> -->
              @endif
              <th width="100">Tes</th>
              <th width="120">Waktu Tes</th>
              <th width="40">Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1 ?>
            @foreach($hasil as $data)
            <tr>
              <td>{{ $i }}</td>
              <td><a href="/admin/hasil/detail/{{ $data->id_hasil }}">{{ ucwords($data->id_user->nama_user) }}</a></td>
              <td>
      				  @if($data->posisi == 1)
      				  	<span>Social Media Manager</span>
                @elseif($data->posisi == 2)
                  <span>Content Writer</span>
                @elseif($data->posisi == 3)
                  <span>Event Manager</span>
                @elseif($data->posisi == 4)
                  <span>Creative and Design Manager</span>
                @elseif($data->posisi == 5)
                  <span>Video Editor</span>
      				  @endif
      			  </td>
              @if(Auth::user()->role == role_admin())
              <!-- <td>{{ get_perusahaan_name($data->id_hrd) }}<br><small class="text-muted">{{ get_hrd_name($data->id_hrd) }}</small></td> -->
              @endif
              <td>{{ $data->nama_tes }}</td>
              <td>
                <span class="d-none">{{ $data->test_at != null ? $data->test_at : '' }}</span>
                {{ $data->test_at != null ? date('d/m/Y', strtotime($data->test_at)) : '-' }}
                <br>
                <small class="text-muted">{{ $data->test_at != null ? date('H:i', strtotime($data->test_at)).' WIB' : '' }}</small>
      			  </td>
              <td>
                <a href="#" class="btn btn-sm btn-block btn-danger btn-delete mb-2" data-id="{{ $data->id_hasil }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
            <?php $i++; ?>
            @endforeach
          </tbody>
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
    generate_datatable("#dataTable");

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
  		if(tes == 0 && hrd == 0) window.location.href = '/admin/hasil/magang';
  		else window.location.href = '/admin/hasil/magang?tes='+tes+'&hrd='+hrd;
    }
    else{
      if(tes == 0) window.location.href = '/admin/hasil/magang';
      else window.location.href = '/admin/hasil/magang?tes='+tes;
    }
	});
</script>

@endsection