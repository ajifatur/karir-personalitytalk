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
                                @if(Auth::user()->role == role('admin'))
                                <th width="200">Perusahaan</th>
                                @endif
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vacancies as $vacancy)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one"></td>
                                <td><a href="{{ route('admin.vacancy.applicant', ['id' => $vacancy->id_lowongan]) }}">{{ $vacancy->judul_lowongan }}</a></td>
                                <td>{{ $vacancy->nama_posisi }}</td>
                                <td>
                                    {{ $vacancy->pelamar }} 
                                    <br>
                                    <span class="badge bg-info">{{ count_pelamar_belum_diseleksi_by_lowongan($vacancy->id_lowongan) }} belum diseleksi</span>
                                    <br>
                                    <span class="badge bg-warning">{{ count_pelamar_belum_dites_by_lowongan($vacancy->id_lowongan) }} belum dites</span>
                                </td>
                                <td>
                                    <span class="d-none">{{ $vacancy->status }}</span>
                                    <select data-id="{{ $vacancy->id_lowongan }}" data-value="{{ $vacancy->status }}" class="form-select form-select-sm status">
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
                                @if(Auth::user()->role == role('admin'))
                                <td>{{ $vacancy->perusahaan }}</td>
                                @endif
                                <td>
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-info btn-url" data-id="{{ $vacancy->id_lowongan }}" data-url="{{ $vacancy->url_lowongan }}" data-bs-toggle="tooltip" title="Lihat URL"><i class="bi-link"></i></a>
                                        <a href="{{ route('admin.vacancy.edit', ['id' => $vacancy->id_lowongan]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $vacancy->id_lowongan }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Launch demo modal
</button>

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

    // Checkbox
    Spandiv.CheckboxOne();
    Spandiv.CheckboxAll();

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