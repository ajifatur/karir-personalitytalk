@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Pelamar</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/lowongan">Lowongan</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Pelamar</li>
    </ol>
  </div>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div>
        <h6 class="m-0"><strong>Posisi:</strong> {{ $lowongan->nama_posisi }}</h6>
      </div>
      <div>
        <h6 class="m-0"><strong>Perusahaan:</strong> {{ get_perusahaan_name($lowongan->id_hrd) }}</h6>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th width="50">No.</th>
              <th>Nama</th>
              <th width="150">No. HP</th>
              <th width="120">Tanggal Daftar</th>
              <th width="120">Hasil</th>
              <th width="80">Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1 ?>
            @foreach($pelamar as $data)
            <tr>
              <td>{{ $i }}</td>
              <td><a href="/admin/pelamar/detail/{{ $data->id_pelamar }}">{{ ucwords($data->nama_lengkap) }}</a></td>
              <td>{{ $data->nomor_hp }}</td>
              <td>
                <span class="d-none">{{ $data->created_at }}</span>
                {{ date('d/m/Y', strtotime($data->created_at)) }}
                <br>
                <span class="small text-muted">{{ date('H:i', strtotime($data->created_at)) }} WIB</span>
      			  </td>
              <td>
                <span class="badge badge-{{ $data->badge_color }}">{{ $data->hasil }}</span>
              </td>
              <td>
                <a href="/admin/pelamar/edit/{{ $data->id_pelamar }}" class="btn btn-sm btn-info mr-2 mb-2 {{ $data->isKaryawan ? 'not-allowed' : '' }}" data-id="{{ $data->id_pelamar }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="#" class="btn btn-sm btn-danger mb-2 {{ $data->isKaryawan ? 'not-allowed' : 'btn-delete' }}" data-id="{{ $data->id_pelamar }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
            <?php $i++; ?>
            @endforeach
          </tbody>
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
    generate_datatable("#dataTable");

    // Button Not Allowed
    $(document).on("click", ".not-allowed", function(e){
      e.preventDefault();
	  alert("Anda tidak diizinkan untuk melakukan aksi pada data ini!");
    });
  });
</script>

@endsection