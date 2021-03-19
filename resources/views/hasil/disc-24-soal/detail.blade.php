@extends('template/admin/main')

@section('content')

  <!-- Page Heading -->
  <div class="page-heading shadow d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Data Hasil Tes</h1>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
      <li class="breadcrumb-item"><a href="/admin/hasil">Hasil Tes</a></li>
      <li class="breadcrumb-item active" aria-current="page">DISC 24 Soal</li>
    </ol>
  </div>

  <!-- Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <div></div>
      @if(Auth::user()->role == role_admin())
      <div>
          <a class="btn btn-sm btn-primary btn-print" href="#">
            <i class="fas fa-print fa-sm fa-fw text-gray-400"></i> Cetak
          </a>
      </div>
      @endif
    </div>
    <div class="card-body">
      <form id="form" class="d-none" method="post" action="/admin/hasil/print" target="_blank">
          {{ csrf_field() }}
          @php
            $posisi_magang = '';
            if($user->role == 6){
                if($user->jenis_kelamin == 1) $posisi_magang = 'Social Media Manager (Magang)';
                elseif($user->jenis_kelamin == 2) $posisi_magang = 'Content Writer (Magang)';
                elseif($user->jenis_kelamin == 3) $posisi_magang = 'Event Manager (Magang)';
                elseif($user->jenis_kelamin == 4) $posisi_magang = 'Creative & Design Manager (Magang)';
                elseif($user->jenis_kelamin == 5) $posisi_magang = 'Video Editor (Magang)';
            }
          @endphp
          <input type="hidden" name="mostChartImage" id="mostChartImage">
          <input type="hidden" name="leastChartImage" id="leastChartImage">
          <input type="hidden" name="changeChartImage" id="changeChartImage">
          <input type="hidden" name="nama" value="{{ $user->nama_user }} {{ $user->role == 6 ? '('.$user->email.')' : '' }}">
          <input type="hidden" name="usia" value="{{ $user->role != 6 ? generate_age($user->tanggal_lahir, $hasil->created_at).' tahun' : '-' }}">
          <input type="hidden" name="jenis_kelamin" value="{{ $user->role != 6 ? $user->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' : '-' }}">
          <input type="hidden" name="posisi" value="{{ $user->role != 6 ? !empty($user_desc) ? $user_desc->nama_posisi.' ('.$role->nama_role.')' : $role->nama_role : $posisi_magang }}">
          <input type="hidden" name="tes" value="{{ $hasil->nama_tes }}">
          <input type="hidden" name="path" value="{{ $hasil->path }}">
          <input type="hidden" name="id_paket" value="{{ $hasil->id_paket }}">
          <input type="hidden" name="hasil" value="{{ json_encode($hasil->hasil) }}">
          <input type="hidden" name="array_selisih" value="{{ json_encode($array_selisih) }}">
          <input type="hidden" name="index" value="{{ json_encode($index) }}">
      </form>
        <div class="row">
          <div class="col-auto mx-auto">
            <div class="table-responsive">
                <table class="table table-borderless table-identity">
                  <tr>
                    <td width="200">Nama: {{ $user->nama_user }} {{ $user->role == 6 ? '('.$user->email.')' : '' }}</td>
					@if($user->role != 6)
                    <td width="200">Usia: {{ $user->role != 6 ? generate_age($user->tanggal_lahir, $hasil->created_at).' tahun' : '-' }}</td>
                    <td width="200">Jenis Kelamin: {{ $user->role != 6 ? $user->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' : '-' }}</td>
					@endif
                    <td width="200">Posisi: {{ $user->role != 6 ? !empty($user_desc) ? $user_desc->nama_posisi.' ('.$role->nama_role.')' : $role->nama_role : $posisi_magang }}</td>
                    <td width="200">Tes: {{ $hasil->nama_tes }}</td>
                  </tr>
                </table>
            </div>
          </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-auto mx-auto">
                        @php
                            $disc = ['D', 'I', 'S', 'C', '*'];
                        @endphp
                        <table class="table-bordered">
                            <thead bgcolor="#bebebe">
                                <tr>
                                <th width="40">#</th>
                                @foreach($disc as $letter)
                                <th width="40">{{ $letter }}</th>
                                @endforeach
                                <th width="40">Tot</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td align="center" bgcolor="#bebebe"><strong>1</strong></td>
                                    <td align="center" bgcolor="#eeeeee">{{ $hasil->hasil['dm'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $hasil->hasil['im'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $hasil->hasil['sm'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $hasil->hasil['cm'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $hasil->hasil['bm'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">24</td>
                                </tr>
                                <tr>
                                    <td align="center" bgcolor="#bebebe"><strong>2</strong></td>
                                    <td align="center" bgcolor="#eeeeee">{{ $hasil->hasil['dl'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $hasil->hasil['il'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $hasil->hasil['sl'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $hasil->hasil['cl'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $hasil->hasil['bl'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">24</td>
                                </tr>
                                <tr>
                                    <td align="center" bgcolor="#bebebe"><strong>3</strong></td>
                                    <td align="center" bgcolor="#eeeeee">{{ $array_selisih['D'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $array_selisih['I'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $array_selisih['S'] }}</td>
                                    <td align="center" bgcolor="#eeeeee">{{ $array_selisih['C'] }}</td>
                                    <td align="center" bgcolor="#333"></td>
                                    <td align="center" bgcolor="#333"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mx-auto">
                <div class="row">
                    <div class="col-md-4">
                        <p class="text-center mb-0 font-weight-bold">Mask Public Self</p>
                        <p class="text-center mb-0 font-weight-bold">MOST</p>
                        <canvas class="mt-3" id="mostChart" width="100" height="200"></canvas>
                    </div>
                    <div class="col-md-4">
                        <p class="text-center mb-0 font-weight-bold">Core Private Self</p>
                        <p class="text-center mb-0 font-weight-bold">LEAST</p>
                        <canvas class="mt-3" id="leastChart" width="100" height="200"></canvas>
                    </div>
                    <div class="col-md-4">
                        <p class="text-center mb-0 font-weight-bold">Mirror Perceived Self</p>
                        <p class="text-center mb-0 font-weight-bold">CHANGE</p>
                        <canvas class="mt-3" id="changeChart" width="100" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-4">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        @php
                            $karakteristik1 = explode(', ', $keterangan->keterangan[$index['most'][0]]['karakteristik']);
                        @endphp
                        <h5>Mask Public Self</h5>
                        <p class="font-weight-bold">{{ $keterangan->keterangan[$index['most'][0]]['tipe'] }}</p>
                        <p>
                            <ul>
                            @foreach($karakteristik1 as $karakter)
                                <li>{{ $karakter }}</li>
                            @endforeach
                            </ul>
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        @php
                            $karakteristik2 = explode(', ', $keterangan->keterangan[$index['least'][0]]['karakteristik']);
                        @endphp
                        <h5>Core Private Self</h5>
                        <p class="font-weight-bold">{{ $keterangan->keterangan[$index['least'][0]]['tipe'] }}</p>
                        <p>
                            <ul>
                            @foreach($karakteristik2 as $karakter)
                                <li>{{ $karakter }}</li>
                            @endforeach
                            </ul>
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        @php
                            $karakteristik3 = explode(', ', $keterangan->keterangan[$index['change'][0]]['karakteristik']);
                        @endphp
                        <h5>Mirror Perceived Self</h5>
                        <p class="font-weight-bold">{{ $keterangan->keterangan[$index['change'][0]]['tipe'] }}</p>
                        <p>
                            <ul>
                            @foreach($karakteristik3 as $karakter)
                                <li>{{ $karakter }}</li>
                            @endforeach
                            </ul>
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5>Deskripsi Kepribadian</h5>
                        <p class="text-justify">{{ $keterangan->keterangan[$index['change'][0]]['deskripsi'] }}</p>
                        <br>
                        <h5>Job Match</h5>
                        <p class="text-justify">{{ $keterangan->keterangan[$index['change'][0]]['job'] }}</p>
                    </div>
                </div>
            </div>
        </div>
      </div>
  </div>
  
@endsection

@section('css-extra')

<style type="text/css">
  table tr th, table tr td {padding: .25rem .5rem; text-align: center;}
  .table-identity {min-width: 1000px};
  .deskripsi {border-style: groove; columns: 2; font-size: smaller;}
  .deskripsi p {margin-bottom: .5rem;}
</style>

@endsection

@section('js-extra')

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

function generateChangeChart(){
    var url = changeChart.toBase64Image();
    document.getElementById("changeChartImage").value = url;
}

var ctx1 = document.getElementById('mostChart').getContext('2d');
var mostChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['D', 'I', 'S', 'C'],
        datasets: [{
            label: 'Score',
            data: [{{ $graph[1]['D'] }}, {{ $graph[1]['I'] }}, {{ $graph[1]['S'] }}, {{ $graph[1]['C'] }}],
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
                    min: -8,
                    max: 8,
                    stepSize: 2
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
            data: [{{ $graph[2]['D'] }}, {{ $graph[2]['I'] }}, {{ $graph[2]['S'] }}, {{ $graph[2]['C'] }}],
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
                    min: -8,
                    max: 8,
                    stepSize: 2
                }
            }]
        },
        bezierCurve : false,
        animation: {
            onComplete: generateLeastChart
        }
    }
});

var ctx3 = document.getElementById('changeChart').getContext('2d');
var changeChart = new Chart(ctx3, {
    type: 'line',
    data: {
        labels: ['D', 'I', 'S', 'C'],
        datasets: [{
            label: 'Score',
            data: [{{ $graph[3]['D'] }}, {{ $graph[3]['I'] }}, {{ $graph[3]['S'] }}, {{ $graph[3]['C'] }}],
            fill: false,
            backgroundColor: '#340369',
            borderColor: '#340369',
            lineTension: 0
        }]
    },
    options: {
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    min: -8,
                    max: 8,
                    stepSize: 2
                }
            }]
        },
        bezierCurve : false,
        animation: {
            onComplete: generateChangeChart
        }
    }
});

$(document).on("click", ".btn-print", function(e){
  e.preventDefault();
  $("#form")[0].submit();
});

// $(document).on("click", ".btn-print", function(e){
//   e.preventDefault();
//   window.print();
// });
</script>

@endsection