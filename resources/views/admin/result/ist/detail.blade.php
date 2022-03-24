@extends('layouts/admin/main')

@section('title', 'Data Hasil Tes: '.$result->user->name)

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Data Hasil Tes</h1>
    <!-- <a href="#" class="btn btn-sm btn-primary btn-print"><i class="bi-printer me-1"></i> Cetak</a> -->
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
                        <button class="nav-link active" id="result-tab" data-bs-toggle="tab" data-bs-target="#result" type="button" role="tab" aria-controls="result" aria-selected="true">Hasil</button>
                    </li>
                    @if(array_key_exists('answers', $result->result))
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="answer-tab" data-bs-toggle="tab" data-bs-target="#answer" type="button" role="tab" aria-controls="answer" aria-selected="false">Jawaban</button>
                    </li>
                    @endif
                </ul>
                <div class="tab-content p-2" id="myTabContent">
                    <div class="tab-pane fade show active" id="result" role="tabpanel" aria-labelledby="result-tab">
                        <div class="row align-items-center">
                            <div class="col-xl-auto">
                                <div class="row">
                                    <div class="col-auto mx-auto">
                                        <table class="table-bordered">
                                            <thead bgcolor="#bebebe">
                                                <tr>
                                                    <th width="70">#</th>
                                                    <th width="70">RW</th>
                                                    <th width="70">SW</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($resultA['RW'] as $key=>$rw)
                                                <tr>
                                                    <td align="center" bgcolor="#bebebe"><strong>{{ $key }}</strong></td>
                                                    <td align="center" bgcolor="#eee">{{ array_key_exists($key, $resultA['RW']) ? $resultA['RW'][$key] : '-' }}</td>
                                                    <td align="center" bgcolor="#eee">{{ array_key_exists($key, $resultA['SW']) ? $resultA['SW'][$key] : '-' }}</td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td align="center" bgcolor="#bebebe"><strong>Total</strong></td>
                                                    <td align="center" bgcolor="#ddd">{{ $resultA['TRW'] }}</td>
                                                    <td align="center" bgcolor="#ddd">{{ $resultA['TSW'] }}</td>
                                                </tr>
                                                <tr class="text-primary">
                                                    <td align="center" bgcolor="#bebebe"><strong>IQ</strong></td>
                                                    <td align="center" bgcolor="#ccc"></td>
                                                    <td align="center" bgcolor="#ccc"><b>{{ $resultA['IQ'] }}</b>*</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p class="mt-2">* IQ termasuk dalam kategori <em>{{ $kategoriIQ }}</em>.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl mt-3 mt-xl-0 mx-auto">
                                <div class="row">
                                    <div class="col-md-8 mx-auto">
                                        <canvas id="chart" width="200" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(array_key_exists('answers', $result->result))
                    <div class="tab-pane fade" id="answer" role="tabpanel" aria-labelledby="answer-tab">
                        <p class="fst-italic">Jawaban dengan background warna oren adalah jawaban <strong>ragu</strong>.</p>
                        <div class="row">
                            @for($i=1; $i<=3; $i++)
                            <div class="col-md-4 mb-2 mb-md-0">
                                <table class="table-bordered">
                                    <thead bgcolor="#bebebe">
                                        <tr>
                                            <th width="45">#</th>
                                            <th width="75">Jawaban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($j=(($i-1)*20)+1; $j<=$i*20; $j++)
                                        <tr>
                                            <td align="center" bgcolor="#bebebe"><strong>{{ $j }}</strong></td>
                                            <td align="center" bgcolor="{{ array_key_exists($j, $result->result['doubts']) && $result->result['doubts'][$j] == true ? '#fcb92c' : '#eeeeee' }}">
                                                {{ array_key_exists($j, $result->result['answers']) ? is_array($result->result['answers'][$j]) ? implode(', ', $result->result['answers'][$j]) : $result->result['answers'][$j] : '' }}
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            @endfor
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <table class="table-bordered">
                                    <thead bgcolor="#bebebe">
                                        <tr>
                                            <th width="45">#</th>
                                            <th width="125">Jawaban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($j=61; $j<=76; $j++)
                                        <tr>
                                            <td align="center" bgcolor="#bebebe"><strong>{{ $j }}</strong></td>
                                            <td align="center" bgcolor="{{ array_key_exists($j, $result->result['doubts']) && $result->result['doubts'][$j] == true ? '#fcb92c' : '#eeeeee' }}">
                                                {{ array_key_exists($j, $result->result['answers']) ? is_array($result->result['answers'][$j]) ? implode(', ', $result->result['answers'][$j]) : $result->result['answers'][$j] : '' }}
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            @for($i=1; $i<=2; $i++)
                            <div class="col-md-4 mb-2 mb-md-0">
                                <table class="table-bordered">
                                    <thead bgcolor="#bebebe">
                                        <tr>
                                            <th width="45">#</th>
                                            <th width="75">Jawaban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($j=(($i-1)*20)+77; $j<($i*20)+77; $j++)
                                        <tr>
                                            <td align="center" bgcolor="#bebebe"><strong>{{ $j }}</strong></td>
                                            <td align="center" bgcolor="{{ array_key_exists($j, $result->result['doubts']) && $result->result['doubts'][$j] == true ? '#fcb92c' : '#eeeeee' }}">
                                                {{ array_key_exists($j, $result->result['answers']) ? is_array($result->result['answers'][$j]) ? implode(', ', $result->result['answers'][$j]) : $result->result['answers'][$j] : '' }}
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            @endfor
                        </div>
                        <div class="row mt-3">
                            @for($i=1; $i<=3; $i++)
                            <div class="col-md-4 mb-2 mb-md-0">
                                <table class="table-bordered">
                                    <thead bgcolor="#bebebe">
                                        <tr>
                                            <th width="45">#</th>
                                            <th width="75">Jawaban</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($j=(($i-1)*20)+117; $j<($i*20)+117; $j++)
                                        <tr>
                                            <td align="center" bgcolor="#bebebe"><strong>{{ $j }}</strong></td>
                                            <td align="center" bgcolor="{{ array_key_exists($j, $result->result['doubts']) && $result->result['doubts'][$j] == true ? '#fcb92c' : '#eeeeee' }}">
                                                {{ array_key_exists($j, $result->result['answers']) ? is_array($result->result['answers'][$j]) ? implode(', ', $result->result['answers'][$j]) : $result->result['answers'][$j] : '' }}
                                            </td>
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
    <input type="hidden" name="name" value="{{ $result->user->name }}">
    <input type="hidden" name="age" value="{{ generate_age($result->user->attribute->birthdate, $result->created_at).' tahun' }}">
    <input type="hidden" name="gender" value="{{ gender($result->user->attribute->gender) }}">
    <input type="hidden" name="position" value="{{ $result->user->attribute->position->name }}">
    <input type="hidden" name="test" value="{{ $result->test->name }}">
    <input type="hidden" name="path" value="{{ $result->test->code }}">
    <input type="hidden" name="image" id="image">
</form>

@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
<script type="text/javascript">

    function generateChart(){
        var url = mostChart.toBase64Image();
        document.getElementById("chart").value = url;
    }

    var ctx1 = document.getElementById('chart').getContext('2d');
    var mostChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['SE','WA','AN','GE','RA','ZR','FA','WU','ME'],
            datasets: [{
                label: 'Score',
                data: [@php echo implode(',', $resultA['SW']) @endphp],
                fill: false,
                backgroundColor: '#4e73df',
                borderColor: '#4e73df',
                lineTension: 0
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 60,
                        max: 130,
                        stepSize: 10
                    }
                }]
            },
            bezierCurve : false,
            animation: {
                onComplete: generateChart
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
</style>

@endsection