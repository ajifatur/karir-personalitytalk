@extends('layouts/admin/main')

@section('title', 'Data Hasil Tes: '.$user->nama_user)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Data Hasil Tes</h1>
    <a href="#" class="btn btn-sm btn-primary btn-print"><i class="bi-printer me-1"></i> Cetak</a>
</div>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Profil</h5></div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Nama:</span>
                        <span>{{ $user->nama_user }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Usia:</span>
                        <span>{{ generate_age($user->tanggal_lahir, $result->created_at).' tahun' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Jenis Kelamin:</span>
                        <span>{{ gender($user->jenis_kelamin) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Jabatan:</span>
                        <span>{{ !empty($user_desc) ? $user_desc->nama_posisi : $role->nama_role }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Role:</span>
                        <span>{{ role($user->role) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-flex justify-content-between">
                        <span>Tes:</span>
                        <span>{{ $result->nama_tes }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xl-9">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Hasil Tes</h5></div>
            <div class="card-body">
                <p class="h4 text-center font-weight-bold mb-5">Tipe: {{ $result->hasil['tipe'] }}</p>
                  @foreach($keterangan->keterangan as $ket)
                      @if($ket['tipe'] == strtolower($result->hasil['tipe']))
                          {!! html_entity_decode($ket['keterangan']) !!}
                      @endif
                  @endforeach
            </div>
        </div>
    </div>
</div>

<form id="form-print" class="d-none" method="post" action="/admin/hasil/print" target="_blank">
    @csrf
    <input type="hidden" name="mostChartImage" id="mostChartImage">
    <input type="hidden" name="leastChartImage" id="leastChartImage">
    <input type="hidden" name="nama" value="{{ $user->nama_user }}">
    <input type="hidden" name="usia" value="{{ generate_age($user->tanggal_lahir, $result->created_at).' tahun' }}">
    <input type="hidden" name="jenis_kelamin" value="{{ gender($user->jenis_kelamin) }}">
    <input type="hidden" name="posisi" value="{{ !empty($user_desc) ? $user_desc->nama_posisi.' ('.role($user->role).')' : role($user->role) }}">
    <input type="hidden" name="tes" value="{{ $result->nama_tes }}">
    <input type="hidden" name="path" value="{{ $result->path }}">
</form>

@endsection

@section('js')

<script type="text/javascript">
    $(document).on("click", ".btn-print", function(e) {
        e.preventDefault();
        $("#form-print").submit();
    });
</script>

@endsection