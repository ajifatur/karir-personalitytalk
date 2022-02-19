@extends('layouts/admin/main')

@section('title', 'Kelola Karyawan')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Kelola Karyawan</h1>
    <div class="btn-group">
        <a href="{{ route('admin.employee.create') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah Karyawan</a>
        <a href="{{ route('admin.employee.export') }}" class="btn btn-sm btn-success"><i class="bi-file-excel me-1"></i> Ekspor Data</a>
    </div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
            @if(Auth::user()->role->is_global === 1)
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
                                <th>Identitas</th>
                                <th width="100">Username</th>
                                <th width="100">Jabatan</th>
                                <th width="80">Status</th>
                                <th width="200">Perusahaan</th>
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
		</div>
	</div>
</div>

<form class="form-delete d-none" method="post" action="{{ route('admin.employee.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable", {
		serverSide: true,
		pageLength: 25,
        url: Spandiv.URL("{{ route('admin.employee.index') }}", {hrd: "{{ Request::query('hrd') }}"}),
        columns: [
            {data: 'checkbox', name: 'checkbox', className: 'text-center'},
            {data: 'name', name: 'name'},
            {data: 'username', name: 'username'},
            {data: 'posisi', name: 'posisi'},
            {data: 'status', name: 'status'},
            {data: 'company', name: 'company', visible: {{ Auth::user()->role->is_global === 1 && Request::query('hrd') == null ? 'true' : 'false' }}},
            {data: 'options', name: 'options', className: 'text-center', orderable: false},
        ],
        order: [2, 'asc']
    });

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");
  
    // Change the HRD
    $(document).on("change", ".card-header select[name=hrd]", function() {
		var hrd = $(this).val();
		if(hrd === "0") window.location.href = Spandiv.URL("{{ route('admin.employee.index') }}");
		else window.location.href = Spandiv.URL("{{ route('admin.employee.index') }}", {hrd: hrd});
    });
</script>

@endsection
