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
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <span>Nama:</span>
                        <span>{{ $user->nama_user }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <span>Usia:</span>
                        <span>{{ generate_age($user->tanggal_lahir, $result->created_at).' tahun' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <span>Jenis Kelamin:</span>
                        <span>{{ gender($user->jenis_kelamin) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <span>Jabatan:</span>
                        <span>{{ !empty($user_desc) ? $user_desc->nama_posisi : $role->nama_role }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
                        <span>Role:</span>
                        <span>{{ role($user->role) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1 d-sm-flex justify-content-between">
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
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="false">Deskripsi</button>
                    </li>
                    @if(array_key_exists('answers', $result->hasil))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="answer-tab" data-bs-toggle="tab" data-bs-target="#answer" type="button" role="tab" aria-controls="answer" aria-selected="false">Jawaban</button>
                    </li>
                    @endif
                </ul>
                <div class="tab-content p-2" id="myTabContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <p class="h4 text-center fw-bold mb-5">Tipe: {{ $result->hasil['tipe'] }}</p>
                        @foreach($keterangan->keterangan as $ket)
                            @if($ket['tipe'] == strtolower($result->hasil['tipe']))
                                {!! html_entity_decode($ket['keterangan']) !!}
                            @endif
                        @endforeach
                    </div>
                    @if(array_key_exists('answers', $result->hasil))
                    <div class="tab-pane fade" id="answer" role="tabpanel" aria-labelledby="answer-tab">
                        <div class="row">
                            @for($i=1; $i<=4; $i++)
                            <div class="col-md-3 col-6 mb-2 mb-md-0">
                                <table class="table-bordered">
                                    <thead bgcolor="#bebebe">
                                        <tr>
                                            <th width="40">#</th>
                                            <th width="40">Jawaban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($j=(($i-1)*16)+1; $j<=$i*16; $j++)
                                        <tr>
                                            <td align="center" bgcolor="#bebebe"><strong>{{ $j }}</strong></td>
                                            <td align="center" bgcolor="#eeeeee">{{ $result->hasil['answers'][$j] }}</td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            @endfor
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-print" class="d-none" method="post" action="{{ route('admin.result.print') }}" target="_blank">
    @csrf
    <input type="hidden" name="id_hasil" value="{{ $result->id_hasil }}">
    <input type="hidden" name="nama" value="{{ $user->nama_user }}">
    <input type="hidden" name="usia" value="{{ generate_age($user->tanggal_lahir, $result->created_at).' tahun' }}">
    <input type="hidden" name="jenis_kelamin" value="{{ gender($user->jenis_kelamin) }}">
    <input type="hidden" name="posisi" value="{{ !empty($user_desc) ? $user_desc->nama_posisi.' ('.$role->nama_role.')' : $role->nama_role }}">
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

@section('css')

<style type="text/css">
    table tr th, table tr td {padding: .25rem .5rem; text-align: center;}
</style>

@endsection