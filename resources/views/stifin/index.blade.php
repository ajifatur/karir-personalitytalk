@extends('template/admin/main')

@section('content')

    <!-- Page Heading -->
    <div class="page-heading shadow d-flex justify-content-between align-items-center">
        <h1 class="h3 text-gray-800">Data STIFIn</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
            <li class="breadcrumb-item"><a href="/admin/stifin">STIFIn</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data STIFIn</li>
        </ol>
    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <div>
                <a class="btn btn-sm btn-primary" href="/admin/stifin/create">
                <i class="fas fa-plus fa-sm fa-fw text-gray-400"></i> Tambah STIFIn
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
                        <th>Nama</th>
                        <th width="150">Tes</th>
                        <th width="150">Perusahaan</th>
                        <th width="80">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stifin as $data)
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->tests->test_name }}</td>
                            <td>
                                {{ $data->hrd->perusahaan }}
                                <br>
                                <small>{{ $data->hrd->nama_lengkap }}</small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="/admin/stifin/print/{{ $data->id }}" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="fa fa-print"></i></a>
                                    <a href="/admin/stifin/edit/{{ $data->id }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $data->id }}" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <form id="form-delete" class="d-none" method="post" action="/admin/stifin/delete">
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
    });
</script>

@endsection