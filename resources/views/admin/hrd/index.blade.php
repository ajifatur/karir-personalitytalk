@extends('layouts/admin/main')

@section('title', 'Kelola HRD')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Kelola HRD</h1>
    <a href="{{ route('admin.hrd.create') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah HRD</a>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
            <div class="card-body">
                @if(Session::get('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-message">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th width="30"><input type="checkbox" class="form-check-input checkbox-all"></th>
                                <th>Nama</th>
                                <th width="100">Username</th>
                                @if(Auth::user()->role == role('admin'))
                                <th width="200">Perusahaan</th>
                                @endif
                                <th width="100">Kunjungan Terakhir</th>
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hrds as $hrd)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one"></td>
                                <td><a href="{{ route('admin.hrd.detail', ['id' => $hrd->id_hrd]) }}">{{ $hrd->nama_lengkap }}</a></td>
                                <td>{{ $hrd->username }}</td>
                                @if(Auth::user()->role == role('admin'))
                                <td>{{ $hrd->perusahaan }}</td>
                                @endif
                                <td>
                                    <span class="d-none">{{ $hrd->last_visit != null ? $hrd->last_visit : "" }}</span>
                                    {{ $hrd->last_visit != null ? date("d/m/Y", strtotime($hrd->last_visit)) : "-" }}
                                    <br>
                                    <small class="text-muted">{{ date("H:i", strtotime($hrd->last_visit))." WIB" }}</small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.hrd.edit', ['id' => $hrd->id_hrd]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $hrd->id_hrd }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
		</div>
	</div>
</div>

<form class="form-delete d-none" method="post" action="{{ route('admin.hrd.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable");

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");
    
    // Checkbox
    Spandiv.CheckboxOne();
    Spandiv.CheckboxAll();
</script>

@endsection