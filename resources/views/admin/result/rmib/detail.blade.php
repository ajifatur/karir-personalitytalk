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
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#table" type="button" role="tab" aria-controls="table" aria-selected="false">Tabel</button>
                    </li>
                    @if(array_key_exists('answers', $result->hasil))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="answer-tab" data-bs-toggle="tab" data-bs-target="#answer" type="button" role="tab" aria-controls="answer" aria-selected="false">Jawaban</button>
                    </li>
                    @endif
                </ul>
                <div class="tab-content p-2" id="myTabContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <h4>Arah Minat:</h4>
                        <ol>
                            @foreach($interests as $key=>$interest)
                            <li>
                                <span class="fw-bold">{{ $interest['name'] }}</span>
                                <br>
                                {{ $interest['description'] }}
                                <br>
                                Contoh: {{ $interest['example'] }}
                            </li>
                            @endforeach
                        </ol>
                        @if(array_key_exists('occupations', $result->hasil))
                        <hr>
                        <h4>Pekerjaan yang paling diinginkan:</h4>
                        <ol>
                            @foreach($result->hasil['occupations'] as $occupation)
                            <li>{{ $occupation }}</li>
                            @endforeach
                        </ol>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="table" role="tabpanel" aria-labelledby="table-tab">
                        <table class="table table-sm table-bordered">
                            <thead bgcolor="#bebebe">
                                <tr>
                                    <th width="20">No.</th>
                                    <th width="100">Kategori</th>
                                    @foreach($letters as $letter)
                                    <th width="40">{{ $letter }}</th>
                                    @endforeach
                                    <th width="40">Jumlah</th>
                                    <th width="40">Rank</th>
                                    <th width="40">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $keyc=>$category)
                                <tr bgcolor="{{ $category_ranks[$keyc] <= 3 ? '#e5e5e5' : '' }}">
                                    <td>{{ ($keyc+1) }}</td>
                                    <td>{{ $category }}</td>
                                    @for($i=0; $i<=8; $i++)
                                        <td class="{{ $keyc == $i ? 'text-primary fw-bold' : '' }}">{{ $sheets[$keyc][$i] }}</td>
                                    @endfor
                                    <td>{{ $sums[$keyc] }}</td>
                                    <td>{{ $category_ranks[$keyc] }}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="11"></td>
                                    <td class="fw-bold">{{ array_sum($sums) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @if(array_key_exists('answers', $result->hasil))
                    <div class="tab-pane fade" id="answer" role="tabpanel" aria-labelledby="answer-tab">
                        <div class="row">
                            @foreach($questions as $key0=>$question)
                                @php $array = json_decode($question->soal, true); @endphp
                                @if(array_key_exists($user->jenis_kelamin, $array))
                                    <?php
                                        $ranks = [];
                                        foreach($result->hasil['answers'][$question->nomor] as $k=>$v):
                                            array_push($ranks, ['key' => $k, 'value' => $v]);
                                        endforeach;
                                        $columns = array_column($ranks, 'value');
                                        array_multisort($columns, SORT_ASC, $ranks);
                                    ?>
                                    <div class="col-md-4 mb-2">
                                        <table class="table table-sm table-bordered">
                                            <thead bgcolor="#bebebe">
                                                <tr>
                                                    <th colspan="2">{{ $letters[$key0] }}</th>
                                                </tr>
                                                <tr>
                                                    <th width="40">Rank</th>
                                                    <th>Pekerjaan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($ranks as $key=>$rank)
                                                <tr>
                                                    <td>{{ ($key+1) }}</td>
                                                    <td>{{ $array[$user->jenis_kelamin][$rank['key']] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endforeach
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
    <input type="hidden" name="posisi" value="{{ !empty($user_desc) ? $user_desc->nama_posisi.' ('.$role->name.')' : $role->name }}">
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