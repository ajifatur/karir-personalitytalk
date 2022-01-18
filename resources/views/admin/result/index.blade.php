@extends('layouts/admin/main')

@section('title', 'Kelola Hasil Tes '.role((int)Request::query('role')))

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Kelola Hasil Tes {{ role((int)Request::query('role')) }}</h1>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
            <div class="card-header d-sm-flex justify-content-end align-items-center">
                <div class="mb-sm-0 mb-2">
                    <select name="test" class="form-select form-select-sm">
                        <option value="0">Semua Tes</option>
                        @foreach($tests as $test)
                        <option value="{{ $test->id_tes }}" {{ Request::query('test') == $test->id_tes ? 'selected' : '' }}>{{ $test->nama_tes }}</option>
                        @endforeach
                    </select>
                </div>
                @if(Auth::user()->role == role('admin'))
                    <div class="ms-sm-2 ms-0">
                        <select name="hrd" class="form-select form-select-sm">
                            <option value="0">Semua Perusahaan</option>
                            @foreach($hrds as $hrd)
                            <option value="{{ $hrd->id_hrd }}" {{ Request::query('hrd') == $hrd->id_hrd ? 'selected' : '' }}>{{ $hrd->perusahaan }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            <hr class="my-0">
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
                                <th width="100">Jabatan</th>
                                <th width="80">Waktu</th>
                                <th width="100">Tes</th>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.result.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTableServerSide("#datatable", {
        url: Spandiv.URL("{{ route('admin.result.index') }}", {role: "{{ Request::query('role') }}", test: "{{ Request::query('test') }}", hrd: "{{ Request::query('hrd') }}"}),
        columns: [
            {data: 'checkbox', name: 'checkbox', className: 'text-center'},
            {data: 'name', name: 'name'},
            {data: 'posisi', name: 'posisi'},
            {data: 'datetime', name: 'datetime'},
            {data: 'tes', name: 'tes', visible: {{ Request::query('test') == null ? 'true' : 'false' }}},
            {data: 'company', name: 'company', visible: {{ Auth::user()->role == role('admin') && Request::query('hrd') == null ? 'true' : 'false' }}},
            {data: 'options', name: 'options', className: 'text-center', orderable: false},
        ],
        order: [3, 'desc']
    });

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");

    // Checkbox
    Spandiv.CheckboxOne();
    Spandiv.CheckboxAll();
  
    // Change the Test and/or the HRD
    $(document).on("change", ".card-header select[name=test], .card-header select[name=hrd]", function() {
        var test = $(".card-header select[name=test]").val();
        var hrd = $(".card-header select[name=hrd]").length === 1 ? $(".card-header select[name=hrd]").val() : null;

        // Redirect
        if(hrd !== null) {
            if(test == 0 && hrd == 0) window.location.href = Spandiv.URL("{{ route('admin.result.index') }}", {role: "{{ Request::query('role') }}"});
            else if(test == 0 && hrd != 0) window.location.href = Spandiv.URL("{{ route('admin.result.index') }}", {role: "{{ Request::query('role') }}", hrd: hrd});
            else if(test != 0 && hrd == 0) window.location.href = Spandiv.URL("{{ route('admin.result.index') }}", {role: "{{ Request::query('role') }}", test: test});
            else window.location.href = Spandiv.URL("{{ route('admin.result.index') }}", {role: "{{ Request::query('role') }}", test: test, hrd: hrd});
        }
        else {
            if(test == 0) window.location.href = Spandiv.URL("{{ route('admin.result.index') }}", {role: "{{ Request::query('role') }}"});
            else window.location.href = Spandiv.URL("{{ route('admin.result.index') }}", {role: "{{ Request::query('role') }}", test: test});
        }
    });
</script>

@endsection