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
                        <button class="nav-link active" id="graph-tab" data-bs-toggle="tab" data-bs-target="#graph" type="button" role="tab" aria-controls="graph" aria-selected="true">Grafik</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="false">Deskripsi</button>
                    </li>
                    @if(array_key_exists('answers', $result->result))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="answer-tab" data-bs-toggle="tab" data-bs-target="#answer" type="button" role="tab" aria-controls="answer" aria-selected="false">Jawaban</button>
                    </li>
                    @endif
                </ul>
                <div class="tab-content p-2" id="myTabContent">
                    <div class="tab-pane fade show active" id="graph" role="tabpanel" aria-labelledby="graph-tab">
                        <div class="row align-items-center">
                            <div class="col-xl-auto">
                                <div class="row">
                                    <div class="col-auto mx-auto">
                                        <table class="table-bordered">
                                            <thead bgcolor="#bebebe">
                                                <tr>
                                                <th width="50" rowspan="2">#</th>
                                                <th width="100">MOST</th>
                                                <th width="100">LEAST</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($disc as $letter)
                                                <tr>
                                                <td align="center" bgcolor="#bebebe"><strong>{{ $letter }}</strong></td>
                                                <td align="center" bgcolor="#eeeeee">{{ array_key_exists($letter, $disc_score_m) ? $disc_score_m[$letter]['score'] : 0 }}</td>
                                                <td align="center" bgcolor="#eeeeee">{{ array_key_exists($letter, $disc_score_l) ? $disc_score_l[$letter]['score'] : 0 }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl mt-3 mt-xl-0 mx-auto">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-center mb-0 fw-bold">Response to the Environment</p>
                                        <p class="text-center mb-0 fw-bold">MOST</p>
                                        <p class="text-center mb-0 fw-bold">Adapted: (<?php echo implode("-", $code_m) ?>)</p>
                                        <canvas class="mt-3" id="mostChart" width="200" height="150"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-center mb-0 fw-bold">Basic Style</p>
                                        <p class="text-center mb-0 fw-bold">LEAST</p>
                                        <p class="text-center mb-0 fw-bold">Natural: (<?php echo implode("-", $code_l) ?>)</p>
                                        <canvas class="mt-3" id="leastChart" width="200" height="150"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="p-2 deskripsi">{!! html_entity_decode($description_result) !!}</div>
                    </div>
                    @if(array_key_exists('answers', $result->result))
                    <div class="tab-pane fade" id="answer" role="tabpanel" aria-labelledby="answer-tab">
                        <div class="row">
                            @for($i=1; $i<=4; $i++)
                            <div class="col-md-3 mb-2 mb-md-0">
                                <table class="table-bordered">
                                    <thead bgcolor="#bebebe">
                                        <tr>
                                            <th width="40">#</th>
                                            <th width="40">Most</th>
                                            <th width="40">Least</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($j=(($i-1)*10)+1; $j<=$i*10; $j++)
                                        <tr>
                                            <td align="center" bgcolor="#bebebe"><strong>{{ $j }}</strong></td>
                                            <td align="center" bgcolor="#eeeeee">{{ $result->result['answers']['m'][$j] }}</td>
                                            <td align="center" bgcolor="#eeeeee">{{ $result->result['answers']['l'][$j] }}</td>
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
    <input type="hidden" name="mostChartImage" id="mostChartImage">
    <input type="hidden" name="leastChartImage" id="leastChartImage">
    <input type="hidden" name="name" value="{{ $result->user->name }}">
    <input type="hidden" name="age" value="{{ generate_age($result->user->attribute->birthdate, $result->created_at).' tahun' }}">
    <input type="hidden" name="gender" value="{{ gender($result->user->attribute->gender) }}">
    <input type="hidden" name="position" value="{{ $result->user->attribute->position->name }}">
    <input type="hidden" name="test" value="{{ $result->test->name }}">
    <input type="hidden" name="path" value="{{ $result->test->code }}">
    <input type="hidden" name="packet_id" value="{{ $result->packet_id }}">
    <input type="hidden" name="disc_score_m" value="{{ json_encode($disc_score_m) }}">
    <input type="hidden" name="disc_score_l" value="{{ json_encode($disc_score_l) }}">
    <input type="hidden" name="most" value="<?php echo implode("-", $code_m) ?>">
    <input type="hidden" name="least" value="<?php echo implode("-", $code_l) ?>">
    <input type="hidden" name="description_code" value="{{ $description_code }}">
</form>

@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
<script type="text/javascript">
    function generateMostChart(){
        var url = mostChart.toBase64Image();
        document.getElementById("mostChartImage").value = url;
    }

    function generateLeastChart(){
        var url = leastChart.toBase64Image();
        document.getElementById("leastChartImage").value = url;
    }

    var ctx1 = document.getElementById('mostChart').getContext('2d');
    var mostChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['D', 'I', 'S', 'C'],
            datasets: [{
                label: 'Score',
                data: [{{ array_key_exists('D', $disc_score_m) ? $disc_score_m['D']['score'] : 0 }}, {{ array_key_exists('I', $disc_score_m) ? $disc_score_m['I']['score'] : 0 }}, {{ array_key_exists('S', $disc_score_m) ? $disc_score_m['S']['score'] : 0 }}, {{ array_key_exists('C', $disc_score_m) ? $disc_score_m['C']['score'] : 0 }}],
                fill: false,
                backgroundColor: '#FF6B8A',
                borderColor: '#FF6B8A',
                lineTension: 0
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: 100,
                        stepSize: 25
                    }
                }]
            },
            bezierCurve : false,
            animation: {
                onComplete: generateMostChart
            }
        }
    });

    var ctx2 = document.getElementById('leastChart').getContext('2d');
    var leastChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['D', 'I', 'S', 'C'],
            datasets: [{
                label: 'Score',
                data: [{{ array_key_exists('D', $disc_score_l) ? $disc_score_l['D']['score'] : 0 }}, {{ array_key_exists('I', $disc_score_l) ? $disc_score_l['I']['score'] : 0 }}, {{ array_key_exists('S', $disc_score_l) ? $disc_score_l['S']['score'] : 0 }}, {{ array_key_exists('C', $disc_score_l) ? $disc_score_l['C']['score'] : 0 }}],
                fill: false,
                backgroundColor: '#fd7e14',
                borderColor: '#fd7e14',
                lineTension: 0
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: 100,
                        stepSize: 25
                    }
                }]
            },
            bezierCurve : false,
            animation: {
                onComplete: generateLeastChart
            }
        }
    });

    $(document).on("click", ".btn-print", function(e) {
        e.preventDefault();
        $("#form-print").submit();
    });
</script>

@endsection

@section('css')

<style type="text/css">
    table tr th, table tr td {padding: .25rem .5rem; text-align: center;}
    .table-identity {min-width: 1000px};
    .deskripsi {border-style: groove;}
    .deskripsi span {font-size: .875rem!important;}
    .deskripsi p {margin-bottom: .5rem; text-align: justify;}
    @media(min-width: 768px) {
        .deskripsi {columns: 2;}
    }
</style>

@endsection