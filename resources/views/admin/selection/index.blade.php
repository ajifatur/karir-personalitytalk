@extends('layouts/admin/main')

@section('title', 'Kelola Seleksi')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Kelola Seleksi</h1>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
            <div class="card-header d-sm-flex justify-content-end align-items-center">
                <div class="mb-sm-0 mb-2">
                    <select name="result" class="form-select form-select-sm">
                        <option value="-1" {{ Request::query('result') == '-1' ? 'selected' : '' }}>Semua Hasil</option>
                        <option value="1" {{ Request::query('result') == '1' ? 'selected' : '' }}>Direkomendasikan</option>
                        <option value="0" {{ Request::query('result') == '0' ? 'selected' : '' }}>Tidak Direkomendasikan</option>
                        <option value="2" {{ Request::query('result') == '2' ? 'selected' : '' }}>Dipertimbangkan</option>
                        <option value="99" {{ Request::query('result') == '99' ? 'selected' : '' }}>Belum Dites</option>
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
                                <th width="100">Username</th>
                                <th width="150">Posisi</th>
                                <th width="80">Hasil</th>
                                <th width="100">Waktu Tes</th>
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selections as $selection)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one"></td>
                                <td>
                                    <a href="{{ route('admin.applicant.detail', ['id' => $selection->id_pelamar]) }}">{{ ucwords($selection->nama_lengkap) }}</a>
                                    <br>
                                    <small class="text-muted"><i class="bi-envelope me-2"></i>{{ $selection->email }}</small>
                                    <br>
                                    <small class="text-muted"><i class="bi-phone me-2"></i>{{ $selection->nomor_hp }}</small>
                                </td>
                                <td>{{ $selection->username }}</td>
                                <td>{{ $selection->nama_posisi }}</td>
                                <td>
                                    @if($selection->hasil == 1)
                                    <span class="badge bg-success">Direkomendasikan</span>
                                    @elseif($selection->hasil == 0)
                                    <span class="badge bg-danger">Tidak Direkomendasikan</span>
                                    @elseif($selection->hasil == 2)
                                    <span class="badge bg-info">Dipertimbangkan</span>
                                    @elseif($selection->hasil == 99)
                                    <span class="badge bg-warning">Belum Dites</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-none">{{ $selection->waktu_wawancara != null ? $selection->waktu_wawancara : '' }}</span>
                                    {{ $selection->waktu_wawancara != null ? date('d/m/Y', strtotime($selection->waktu_wawancara)) : '-' }}
                                    <br>
                                    <small class="text-muted">{{ $selection->waktu_wawancara != null ? date('H:i', strtotime($selection->waktu_wawancara)).' WIB' : '' }}</small>
                                </td>
                                <td align="center">
                                    <div class="btn-group">
                                        @if($selection->hasil == 1 && $selection->isEmployee == false)
                                        <a href="#" class="btn btn-sm btn-success btn-convert" data-id="{{ $selection->id_seleksi }}" data-bs-toggle="tooltip" title="Lantik Menjadi Karyawan"><i class="bi-check-circle"></i></a>
                                        @endif
                                        @if($selection->isEmployee == false)
                                        <a href="#" class="btn btn-sm btn-warning btn-set-test" data-id="{{ $selection->id_seleksi }}" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        @endif
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $selection->id_seleksi }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.selection.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

<form class="form-convert d-none" method="post" action="{{ route('admin.selection.convert') }}">
    @csrf
    <input type="hidden" name="id">
</form>

<!-- Modal -->
<div class="modal fade" id="modal-set-test" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Atur Tes</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('admin.selection.update') }}">
                @csrf
                <input type="hidden" name="id" value="{{ old('id') }}">
                <div class="modal-body">
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Hasil <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="result" class="form-select form-select-sm {{ $errors->has('result') ? 'border-danger' : '' }}">
                                <option value="" disabled selected>--Pilih--</option>
                                <option value="1" {{ old('result') == '1' ? 'selected' : '' }}>Direkomendasikan</option>
                                <option value="0" {{ old('result') == '0' ? 'selected' : '' }}>Tidak Direkomendasikan</option>
                                <option value="2" {{ old('result') == '2' ? 'selected' : '' }}>Dipertimbangkan</option>
                                <option value="99" {{ old('result') == '99' ? 'selected' : '' }}>Belum Dites</option>
                            </select>
                            @if($errors->has('result'))
                            <div class="small text-danger">{{ $errors->first('result') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tanggal <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="date" class="form-control form-control-sm {{ $errors->has('date') ? 'border-danger' : '' }}" value="{{ old('date') }}" autocomplete="off">
                                <span class="input-group-text {{ $errors->has('date') ? 'border-danger' : '' }}"><i class="bi-calendar2"></i></span>
                            </div>
                            @if($errors->has('date'))
                            <div class="small text-danger">{{ $errors->first('date') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Jam <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="time" class="form-control form-control-sm {{ $errors->has('time') ? 'border-danger' : '' }}" value="{{ old('time') }}" autocomplete="off">
                                <span class="input-group-text {{ $errors->has('time') ? 'border-danger' : '' }}"><i class="bi-clock"></i></span>
                            </div>
                            @if($errors->has('time'))
                            <div class="small text-danger">{{ $errors->first('time') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-lg-2 col-md-3 col-form-label">Tempat <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="place" class="form-control form-control-sm {{ $errors->has('place') ? 'border-danger' : '' }}" value="{{ old('place') }}">
                            @if($errors->has('place'))
                            <div class="small text-danger">{{ $errors->first('place') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="bi-x-circle me-1"></i> Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable");

    // DatePicker
    Spandiv.DatePicker("input[name=date]");

    // ClockPicker
    Spandiv.ClockPicker("input[name=time]");

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");

    // Button Convert
    $(document).on("click", ".btn-convert", function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        $(".form-convert").find("input[name=id]").val(id);
        Spandiv.SwalWarning("Anda yakin ingin mengonversi akun pelamar ke karyawan?", ".form-convert");
    });
  
    // Change the Result and/or the HRD
    $(document).on("change", ".card-header select[name=result], .card-header select[name=hrd]", function() {
        var result = $(".card-header select[name=result]").val();
        var hrd = $(".card-header select[name=hrd]").length === 1 ? $(".card-header select[name=hrd]").val() : null;

        // Redirect
        if(hrd !== null) {
            if(result == -1 && hrd == 0) window.location.href = Spandiv.URL("{{ route('admin.selection.index') }}");
            else window.location.href = Spandiv.URL("{{ route('admin.selection.index') }}", {result: result, hrd: hrd});
        }
        else {
            if(result == -1) window.location.href = Spandiv.URL("{{ route('admin.selection.index') }}");
            else window.location.href = Spandiv.URL("{{ route('admin.selection.index') }}", {result: result});
        }
    });

    // Button Set Test
    $(document).on("click", ".btn-set-test", function(e) {
        e.preventDefault();
        var id = $(this).data("id");
        $.ajax({
            type: "get",
            url: "{{ route('api.selection.detail') }}",
            data: {_token: "{{ csrf_token() }}", id: id},
            success: function(response) {
                // Set Test Form
                $("#modal-set-test").find("input[name=id]").val(response.id_seleksi);
                $("#modal-set-test").find("select[name=result]").val(response.hasil);
                $("#modal-set-test").find("input[name=date]").val(response.tanggal_wawancara);
                $("#modal-set-test").find("input[name=time]").val(response.waktu_wawancara.split(" ")[1].substr(0,5));
                $("#modal-set-test").find("input[name=place]").val(response.tempat_wawancara);

                // Add/Remove Disabled Attribute (Optional)
                if(response.hasil === 1 || response.hasil === 2 || response.hasil === 0) {
                    $("#modal-set-test").find("input[name=date]").attr("disabled","disabled");
                    $("#modal-set-test").find("input[name=time]").attr("disabled","disabled");
                    $("#modal-set-test").find("input[name=place]").attr("disabled","disabled");
                }
                else {
                    $("#modal-set-test").find("input[name=date]").removeAttr("disabled");
                    $("#modal-set-test").find("input[name=time]").removeAttr("disabled");
                    $("#modal-set-test").find("input[name=place]").removeAttr("disabled");
                }

                // Show Modal
                var modal = bootstrap.Modal.getOrCreateInstance(document.querySelector("#modal-set-test"));
                modal.show();
            }
        });
    });

    // Change Result on Set Test
    $(document).on("change", "#modal-set-test select[name=result]", function(e) {
        e.preventDefault();
        var result = $(this).val();
        if(result === "1" || result === "2" || result === "0") {
            $("#modal-set-test").find("input[name=date]").attr("disabled","disabled");
            $("#modal-set-test").find("input[name=time]").attr("disabled","disabled");
            $("#modal-set-test").find("input[name=place]").attr("disabled","disabled");
        }
        else if(result === "99") {
            $("#modal-set-test").find("input[name=date]").removeAttr("disabled");
            $("#modal-set-test").find("input[name=time]").removeAttr("disabled");
            $("#modal-set-test").find("input[name=place]").removeAttr("disabled");
        }
    });
</script>

@if(count($errors) > 0)
<script type="text/javascript">
    // Show Modal
    var modal = bootstrap.Modal.getOrCreateInstance(document.querySelector("#modal-set-test"));
    modal.show();
</script>
@endif

@endsection