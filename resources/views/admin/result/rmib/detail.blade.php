@extends('layouts/admin/main')

@section('title', 'Data Hasil Tes: '.$result->user->name)

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
                    <li class="list-group-item px-0 py-1">
                        <span class="d-block fw-bold">Nama:</span>
                        <span class="d-block">{{ $result->user->name }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1">
                        <span class="d-block fw-bold">Usia:</span>
                        <span class="d-block">{{ generate_age($result->user->attribute->birthdate, $result->created_at).' tahun' }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1">
                        <span class="d-block fw-bold">Jenis Kelamin:</span>
                        <span class="d-block">{{ gender($result->user->attribute->gender) }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1">
                        <span class="d-block fw-bold">Jabatan:</span>
                        <span class="d-block">{{ $result->user->attribute->position->name }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1">
                        <span class="d-block fw-bold">Role:</span>
                        <span class="d-block">{{ $result->user->role->name }}</span>
                    </li>
                    <li class="list-group-item px-0 py-1">
                        <span class="d-block fw-bold">Tes:</span>
                        <span class="d-block">{{ $result->test->name }}</span>
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
                    @if(array_key_exists('answers', $result->result))
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
                        @if(array_key_exists('occupations', $result->result))
                        <hr>
                        <h4>Pekerjaan yang paling diinginkan:</h4>
                        <ol>
                            @foreach($result->result['occupations'] as $occupation)
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
                    @if(array_key_exists('answers', $result->result))
                    <div class="tab-pane fade" id="answer" role="tabpanel" aria-labelledby="answer-tab">
                        <div class="row">
                            @foreach($questions as $key0=>$question)
                                @php $array = json_decode($question->description, true); @endphp
                                @if(array_key_exists($result->user->attribute->gender, $array))
                                    <?php
                                        $ranks = [];
                                        foreach($result->result['answers'][$question->number] as $k=>$v):
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
                                                    <td>{{ $array[$result->user->attribute->gender][$rank['key']] }}</td>
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
    <input type="hidden" name="id" value="{{ $result->id }}">
    <input type="hidden" name="name" value="{{ $result->user->name }}">
    <input type="hidden" name="age" value="{{ generate_age($result->user->attribute->birthdate, $result->created_at).' tahun' }}">
    <input type="hidden" name="gender" value="{{ gender($result->user->attribute->gender) }}">
    <input type="hidden" name="position" value="{{ $result->user->attribute->position->name }}">
    <input type="hidden" name="test" value="{{ $result->test->name }}">
    <input type="hidden" name="path" value="{{ $result->test->code }}">
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