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
                        <option value="1" {{ Request::query('result') == '1' ? 'selected' : '' }}>Lolos</option>
                        <option value="0" {{ Request::query('result') == '0' ? 'selected' : '' }}>Tidak Lolos</option>
                        <option value="99" {{ Request::query('result') == '99' ? 'selected' : '' }}>Belum Dites</option>
                    </select>
                </div>
                @if(Auth::user()->role == role('admin'))
                    <div class="ms-sm-2 ms-0">
                        <select name="hrd" class="form-select form-select-sm">
                            <option value="0">Semua Perusahaan</option>
                            @foreach(get_hrd() as $hrd)
                            <option value="{{ $hrd->id_hrd }}" {{ Request::query('hrd') == $hrd->id_hrd ? 'selected' : '' }}>{{ $hrd->perusahaan }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
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
                                    <span class="badge bg-success">Lolos</span>
                                    @elseif($selection->hasil == 0)
                                    <span class="badge bg-danger">Tidak Lolos</span>
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
                                        <a href="#" class="btn btn-sm btn-success btn-convert" data-id="{{ $selection->id_seleksi }}" data-bs-toggle="tooltip" title="Lantik Menjadi Karyawan"><i class="bi-check"></i></a>
                                        @endif
                                        <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
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