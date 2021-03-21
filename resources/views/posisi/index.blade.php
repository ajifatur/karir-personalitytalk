@extends('template/admin/main')

@section('content')

    <!-- Page Heading -->
    <div class="page-heading shadow d-flex justify-content-between align-items-center">
        <h1 class="h3 text-gray-800">Data Jabatan</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
            <li class="breadcrumb-item"><a href="/admin/posisi">Jabatan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Jabatan</li>
        </ol>
    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <div>
                <a class="btn btn-sm btn-primary" href="/admin/posisi/create">
                    <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Tambah Jabatan
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
            @if(Session::get('message'))
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
                            <th width="150">Jabatan</th>
                            <th width="150">Tes</th>
                            <th>Keahlian</th>
                            <th width="50">Karyawan</th>
                            @if(Auth::user()->role == role_admin())
                            <th width="200">Perusahaan</th>
                            @endif
                            <th width="60">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posisi as $data)
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>{{ ucwords($data->nama_posisi) }}</td>
                            <td>{{ $data->tes }}</td>
                            <td>{{ $data->keahlian }}</td>
                            <td>{{ number_format(count_karyawan_by_jabatan($data->id_posisi),0,'.','.') }}</td>
                            @if(Auth::user()->role == role_admin())
                            <td>{{ $data->perusahaan }}<br><small class="text-muted">{{ $data->nama_lengkap }}</small></td>
                            @endif
                            <td>
                                <div class="btn-group">
                                    <a href="/admin/posisi/edit/{{ $data->id_posisi }}" class="btn btn-sm btn-warning" data-id="{{ $data->id_posisi }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $data->id_posisi }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <form id="form-delete" class="d-none" method="post" action="/admin/posisi/delete">
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
    // Call the dataTables jQuery plugin
    generate_datatable("#dataTable");

    // Button Not Allowed
    $(document).on("click", ".not-allowed", function(e){
        e.preventDefault();
    });
	
	// Change HRD
	$(document).on("change", "#hrd", function(){
		var hrd = $(this).val();
		if(hrd == 0) window.location.href = '/admin/posisi';
		else window.location.href = '/admin/posisi?hrd='+hrd;
	});
</script>

@endsection