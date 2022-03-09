@extends('layouts/admin/main')

@section('title', 'Kelola Lowongan')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Kelola Lowongan</h1>
    <a href="{{ route('admin.vacancy.create') }}" class="btn btn-sm btn-primary"><i class="bi-plus me-1"></i> Tambah Lowongan</a>
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
                                <th>Jabatan</th>
                                <th width="100">Pelamar</th>
                                <th width="100">Status</th>
                                <th width="100">Waktu</th>
                                @if(Auth::user()->role->is_global === 1 && Request::query('company') == null)
                                <th width="200">Perusahaan</th>
                                @endif
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vacancies as $vacancy)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one"></td>
                                <td><a href="{{ route('admin.vacancy.applicant', ['id' => $vacancy->id]) }}">{{ $vacancy->name }}</a></td>
                                <td>{{ $vacancy->position->name }}</td>
                                <td>{{ $vacancy->applicants }}</td>
                                <td>
                                    <span class="d-none">{{ $vacancy->status }}</span>
                                    <select data-id="{{ $vacancy->id }}" data-value="{{ $vacancy->status }}" class="form-select form-select-sm status">
                                        <option value="1" {{ $vacancy->status == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ $vacancy->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </td>
                                <td>
                                    <span class="d-none">{{ $vacancy->created_at }}</span>
                                    {{ date('d/m/Y', strtotime($vacancy->created_at)) }}
                                    <br>
                                    <small class="text-muted">{{ date('H:i', strtotime($vacancy->created_at)) }} WIB</span>
                                </td>
                                @if(Auth::user()->role->is_global === 1 && Request::query('company') == null)
                                <td>{{ $vacancy->company->name }}</td>
                                @endif
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-info btn-url" data-id="{{ $vacancy->id }}" data-url="{{ $vacancy->code }}" data-bs-toggle="tooltip" title="Lihat URL"><i class="bi-link"></i></a>
                                        <a href="{{ route('admin.vacancy.edit', ['id' => $vacancy->id]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $vacancy->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.vacancy.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

<!-- Modal -->
<div class="modal fade" id="modal-url" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">URL Formulir</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Berikut adalah URL yang digunakan untuk menuju ke formulir pendaftaran lowongan:</p>
                <div class="input-group">
                    <input type="text" id="url" class="form-control form-control-sm" value="{{ url('/') }}" readonly>
                    <button class="btn btn-sm btn-outline-primary btn-copy" type="button" data-bs-toggle="tooltip" title="Salin ke Clipboard"><i class="bi-clipboard"></i></button>
                    <button class="btn btn-sm btn-outline-primary btn-link" type="button" data-bs-toggle="tooltip" title="Kunjungi URL"><i class="bi-link"></i></button>
                </div>
                <input type="hidden" id="url-root" value="{{ url('/') }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable");

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");
  
    // Change the company
    $(document).on("change", ".card-header select[name=company]", function() {
        var company = $(this).val();
        if(company === "0") window.location.href = Spandiv.URL("{{ route('admin.vacancy.index') }}");
        else window.location.href = Spandiv.URL("{{ route('admin.vacancy.index') }}", {company: company});
    });

    // Button URL
    $(document).on("click", ".btn-url", function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        var url = $(this).data("url");
        var url_root = $("#url-root").val();
        $("#url").val(url_root + '/lowongan/' + url);
        $(".btn-link").attr('data-url', url_root + '/lowongan/' + url);
        var modal = bootstrap.Modal.getOrCreateInstance(document.querySelector("#modal-url"));
        modal.show();
    });

    // Button Copy to Clipboard
    $(document).on("click", ".btn-copy", function(e) {
        e.preventDefault();
        var url = $(this).data("url");
        document.getElementById("url").select();
        document.getElementById("url").setSelectionRange(0, 99999);
        document.execCommand("copy");
        $(this).attr("data-bs-original-title", "Tersalin!");
        $(this).tooltip("show");
        $(this).attr("data-bs-original-title", "Salin ke Clipboard");
    });

    // Button Link
    $(document).on("click", ".btn-link", function(e) {
        e.preventDefault();
        var url = $(this).data("url");
        window.open(url, '_blank');
    });

    // Change Status
    $(document).on("change", ".status", function() {
        var status_before = $(this).data("value");
        var id = $(this).data("id");
        var status = $(this).val();
        $(this).find("option[value=" + status_before + "]").prop("selected", true);
        var word = status == 1 ? "mengaktifkan" : "menonaktifkan";
        var ask = confirm("Anda yakin ingin " + word + " data ini?");
        if(ask) {
            $.ajax({
                type: "post",
                url: "{{ route('admin.vacancy.update-status') }}",
                data: {_token: "{{ csrf_token() }}", id: id, status: status},
                success: function(response) {
                    if(response == "Berhasil mengupdate status!") {
                        alert(response);
                        window.location.href = "{{ route('admin.vacancy.index') }}";
                    }
                }
            });
        }
    });
</script>

@endsection