@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Karyawan</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/karyawan">Karyawan</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Karyawan</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div>
        <a class="btn btn-sm btn-primary" href="/admin/karyawan/create">
          <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Tambah Karyawan
        </a>
        <a class="btn btn-sm btn-primary" href="/admin/karyawan/export{{ isset($_GET) && isset($_GET['hrd']) ? '?hrd='.$_GET['hrd'] : '' }}">
          <i class="fas fa-file-excel fa-sm fa-fw text-gray-400"></i> Ekspor Data
        </a>
        @if(Auth::user()->role == role_hrd())
        <a class="btn btn-sm btn-primary btn-import" href="#" data-toggle="modal" data-target="#modal-import">
          <i class="fas fa-download fa-sm fa-fw text-gray-400"></i> Impor Data
        </a>
        @endif
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
              <th width="150">Jabatan</th>
              <th width="100">Status</th>
              @if(Auth::user()->role == role_admin())
              <th width="200">Perusahaan</th>
              @endif
              <th width="80">Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1 ?>
            @foreach($karyawan as $data)
            <tr>
              <td>{{ $i }}</td>
              <td><a href="/admin/karyawan/detail/{{ $data->id_karyawan }}">{{ ucwords($data->nama_lengkap) }}</a></td>
              <td>{{ $data->id_user->username }}</td>
              <td>{{ $data->posisi != false ? $data->posisi->nama_posisi : '-' }}</td>
              <td><span class="badge {{ $data->status == 1 ? 'badge-success' : 'badge-danger' }}">{{ $data->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</span></td>
              @if(Auth::user()->role == role_admin())
              <td>{{ $data->id_hrd->perusahaan }}<br><small class="text-muted">{{ $data->id_hrd->nama_lengkap }}</small></td>
              @endif
              <td>
                <a href="/admin/karyawan/edit/{{ $data->id_karyawan }}" class="btn btn-sm btn-info mr-2 mb-2" data-id="{{ $data->id_karyawan }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                <a href="#" class="btn btn-sm btn-danger btn-delete mb-2" data-id="{{ $data->id_karyawan }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
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

  <div class="modal" id="modal-import">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form method="post" action="/admin/karyawan/import" enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title">Impor Data Karyawan</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">
            {{ csrf_field() }}
            <p>
              Tata cara mengimpor data karyawan:
              <ol>
                <li>Ekspor terlebih dahulu data karyawan <strong><a href="/admin/karyawan/export">Disini</a></strong>.</li>
                <li>Jika ingin menambah karyawan baru, tambahkan data di bawah baris data terakhir dari file yang sudah di-ekspor tadi.</li>
                <li>Pastikan semua kolom tidak boleh kosong.</li>
                <li>Import data dari file excel yang sudah di-update tadi.</li>
              </ol>
            </p>
            <input type="file" name="file" id="file" class="" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
            <button class="btn btn-sm btn-primary btn-file d-none"><i class="fa fa-folder-open mr-2"></i>Pilih File...</button>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary btn-submit-import" type="submit" disabled>Impor Data</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
@endsection

@section('js-extra')

<!-- JavaScripts -->
<script type="text/javascript">
  // Call the dataTables jQuery plugin
  generate_datatable("#dataTable");

  // Button Not Allowed
  $(document).on("click", ".not-allowed", function(e){
    e.preventDefault();
  });

	// Change HRD
	$(document).on("change", "#hrd", function(){
		var hrd = $(this).val();
		if(hrd == 0) window.location.href = '/admin/karyawan';
		else window.location.href = '/admin/karyawan?hrd='+hrd;
	});

  // Change Input File
  $(document).on("change", "#file", function(){
    $(".btn-submit-import").removeAttr("disabled");
  });

  // Tutup Modal Import
  $("#modal-import").on("hidden.bs.modal", function(e){
    $("#file").val(null);
    $(".btn-submit-import").attr("disabled","disabled");
  });
</script>

@endsection