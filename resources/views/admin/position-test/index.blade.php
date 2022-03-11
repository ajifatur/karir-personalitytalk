@extends('layouts/admin/main')

@section('title', 'Kelola Jabatan Tes')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Kelola Jabatan Tes</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            @if(Auth::user()->role->is_global === 1)
            <div class="card-header d-sm-flex justify-content-end align-items-center">
                <div></div>
                <div class="ms-sm-2 ms-0">
                    <select name="company" class="form-select form-select-sm">
                        <option value="0">Semua Perusahaan</option>
                        @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ Request::query('company') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="my-0">
            @endif
            @if($company)
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
                                <th>Jabatan</th>
                                @foreach($tests as $test)
                                <th width="50">{{ $test->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($positions) > 0)
                                @foreach($positions as $position)
                                    <tr data-id="{{ $position->id }}">
                                        <td>{{ $position->name }}</td>
                                        @foreach($tests as $test)
                                        <td align="center">
                                            <input class="form-check-input" type="checkbox" data-test="{{ $test->id }}" data-position="{{ $position->id }}" {{ in_array($test->id, $position->tests()->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
	</div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed top-0 end-0 d-none">
    <div class="toast align-items-center text-white bg-success border-0" id="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable", {
        pageLength: -1,
        orderAll: true
    });

    // Change the company
    $(document).on("change", ".card-header select[name=company]", function() {
        var company = $(this).val();
        if(company === "0") window.location.href = Spandiv.URL("{{ route('admin.position-test.index') }}");
        else window.location.href = Spandiv.URL("{{ route('admin.position-test.index') }}", {company: company});
    });

    // Change Status
    $(document).on("click", "#datatable .form-check-input", function(e) {
        e.preventDefault();
        if(typeof Pace !== "undefined") Pace.restart();
        var isChecked = $(this).prop("checked") == true ? 1 : 0;
        var position = $(this).data("position");
        var test = $(this).data("test");
        $.ajax({
            type: "post",
            url: "{{ route('admin.position-test.change') }}",
            data: {_token: "{{ csrf_token() }}", isChecked: isChecked, position: position, test: test},
            success: function(response) {
                if(response == "Berhasil mengganti status.") {
                    $("#toast").hasClass("bg-danger") ? $("#toast").removeClass("bg-danger") : '';
                    !$("#toast").hasClass("bg-success") ? $("#toast").addClass("bg-success") : '';
                    e.target.checked = !e.target.checked;
                }
                else {
                    $("#toast").hasClass("bg-success") ? $("#toast").removeClass("bg-success") : '';
                    !$("#toast").hasClass("bg-danger") ? $("#toast").addClass("bg-danger") : '';
                }
                Spandiv.Toast("#toast", response);
            }
        });
    });
</script>

@endsection

@section('css')

<style type="text/css">
    .table tr td:not(:first-child) .form-check-input {height: 1.25rem; width: 1.25rem;}
</style>

@endsection