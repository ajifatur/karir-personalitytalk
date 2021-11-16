@extends('layouts/admin/main')

@section('title', 'Kelola Pelamar')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Kelola Pelamar</h1>
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
                                <th>Identitas</th>
                                <th width="100">Username</th>
                                <th width="100">Jabatan</th>
                                <th width="80">Waktu</th>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.applicant.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTableServerSide("#datatable", {
        url: Spandiv.URL("{{ route('admin.applicant.index') }}", {hrd: "{{ Request::query('hrd') }}"}),
        columns: [
            {data: 'checkbox', name: 'checkbox', className: 'text-center'},
            {data: 'name', name: 'name'},
            {data: 'username', name: 'username'},
            {data: 'posisi', name: 'posisi'},
            {data: 'datetime', name: 'datetime'},
            {data: 'company', name: 'company', visible: {{ Auth::user()->role == role('admin') && Request::query('hrd') == null ? 'true' : 'false' }}},
            {data: 'options', name: 'options', className: 'text-center', orderable: false},
        ],
        order: [4, 'desc']
    });

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");

    // Checkbox
    Spandiv.CheckboxOne();
    Spandiv.CheckboxAll();
  
    // Change the HRD
    $(document).on("change", ".card-header select[name=hrd]", function() {
        var hrd = $(this).val();
        if(hrd === "0") window.location.href = Spandiv.URL("{{ route('admin.applicant.index') }}");
        else window.location.href = Spandiv.URL("{{ route('admin.applicant.index') }}", {hrd: hrd});
    });
</script>

@endsection