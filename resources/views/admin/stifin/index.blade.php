@extends('layouts/admin/main')

@section('title', 'Kelola STIFIn')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Kelola STIFIn</h1>
    <a href="{{ route('admin.stifin.create') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah STIFIn</a>
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
                                <th width="150">Tipe</th>
                                @if(Auth::user()->role->is_global === 1)
                                <th width="200">Perusahaan</th>
                                @endif
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stifins as $stifin)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one"></td>
                                <td>{{ $stifin->name }}</td>
                                <td>{{ $stifin->tests->test_name }}</td>
                                @if(Auth::user()->role->is_global === 1)
                                <td>{{ $stifin->hrd->perusahaan }}</td>
                                @endif
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.stifin.print', ['id' => $stifin->id]) }}" target="_blank" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Cetak"><i class="bi-printer"></i></a>
                                        <a href="{{ route('admin.stifin.edit', ['id' => $stifin->id]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $stifin->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.stifin.delete') }}">
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