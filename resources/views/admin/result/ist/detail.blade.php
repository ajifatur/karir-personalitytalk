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
                                <canvas id="chart" width="200" height="150"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-print" class="d-none" method="post" action="/admin/hasil/print" target="_blank">
    @csrf
    <input type="hidden" name="nama" value="{{ $user->nama_user }}">
    <input type="hidden" name="usia" value="{{ generate_age($user->tanggal_lahir, $result->created_at).' tahun' }}">
    <input type="hidden" name="jenis_kelamin" value="{{ gender($user->jenis_kelamin) }}">
    <input type="hidden" name="posisi" value="{{ !empty($user_desc) ? $user_desc->nama_posisi.' ('.role($user->role).')' : role($user->role) }}">
    <input type="hidden" name="tes" value="{{ $result->nama_tes }}">
    <input type="hidden" name="path" value="{{ $result->path }}">
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