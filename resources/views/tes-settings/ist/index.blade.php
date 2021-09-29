@extends('template/admin/main')

@section('content')

    <!-- Page Heading -->
    <div class="page-heading shadow d-flex justify-content-between align-items-center">
        <h1 class="h3 text-gray-800">Pengaturan Tes</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
            <li class="breadcrumb-item"><a href="#">Pengaturan Tes</a></li>
            <li class="breadcrumb-item active" aria-current="page">IST</li>
        </ol>
    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
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
                            <th>Paket Soal</th>
                            <th width="100">Waktu</th>
                            <th width="100">Autentikasi</th>
                            <th width="100">Kode</th>
                            <th width="40">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paket as $data)
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>
                                {{ $data->nama_paket }}
                                <br>
                                <small class="text-muted">Part: {{ $data->part }}</small>
                            </td>
                            <td>
                                <strong>Pengerjaan:</strong><br>
                                {{ tes_settings($data->id_paket, 'waktu_pengerjaan') != '' ? tes_settings($data->id_paket, 'waktu_pengerjaan').' detik' : '-' }}
                                <br><br>
                                <strong>Hafalan:</strong><br>
                                {{ tes_settings($data->id_paket, 'waktu_hafalan') != '' ? tes_settings($data->id_paket, 'waktu_pengerjaan').' detik' : '-' }}
                            </td>
                            <td>{{ tes_settings($data->id_paket, 'is_auth') === 1 ? 'Ya' : 'Tidak' }}</td>
                            <td>{{ tes_settings($data->id_paket, 'is_auth') === 1 ? tes_settings($data->id_paket, 'access_token') : '-' }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="/admin/tes/settings/{{ $tes->path }}/{{ $data->id_paket }}" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
</script>

@endsection