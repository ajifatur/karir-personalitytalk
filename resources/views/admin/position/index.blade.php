@extends('layouts/admin/main')

@section('title', 'Kelola Jabatan')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Kelola Jabatan</h1>
    <a href="{{ route('admin.position.create') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah Jabatan</a>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
            @if(Auth::user()->role == role('admin'))
            <div class="card-header d-sm-flex justify-content-end align-items-center">
                <div></div>
                <div class="ms-sm-2 ms-0">
                    <select name="hrd" class="form-select form-select-sm">
                        <option value="0">Semua Perusahaan</option>
                        @foreach($hrds as $hrd)
                        <option value="{{ $hrd->id_hrd }}" {{ Request::query('hrd') == $hrd->id_hrd ? 'selected' : '' }}>{{ $hrd->perusahaan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="my-0">
            @endif
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
                                <th width="80">Karyawan</th>
                                @if(Auth::user()->role == role('admin') && Request::query('hrd') == null)
                                <th width="200">Perusahaan</th>
                                @endif
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($positions as $position)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one"></td>
                                <td>{{ $position->nama_posisi }}</td>
                                <td align="right">{{ number_format(count_karyawan_by_jabatan($position->id_posisi),0,',',',') }}</td>
                                @if(Auth::user()->role == role('admin') && Request::query('hrd') == null)
                                <td>{{ $position->perusahaan }}</td>
                                @endif
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.position.edit', ['id' => $position->id_posisi]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $position->id_posisi }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.position.delete') }}">
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
  
    // Change the HRD
    $(document).on("change", ".card-header select[name=hrd]", function() {
        var hrd = $(this).val();
        if(hrd === "0") window.location.href = Spandiv.URL("{{ route('admin.position.index') }}");
        else window.location.href = Spandiv.URL("{{ route('admin.position.index') }}", {hrd: hrd});
    });
</script>

@endsection