<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" integrity="sha256-aa0xaJgmK/X74WM224KMQeNQC2xYKwlAt08oZqjeF0E=" crossorigin="anonymous" />
	<title>DISC Test</title>
</head>
<body>
	<div class="container-fluid mt-5" style="margin-bottom: 120px;">
		<div class="card bg-light">
			<div class="card-title py-3">
				<h1 class="text-center">DISC TEST RESULT</h1>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-auto ml-auto">
						<a href="/disc/test/print-pdf" class="btn btn-sm btn-primary btn-print"><i class="fa fa-print"></i> Print</a>
					</div>
				</div>
				<div class="row">
					<div class="col-auto mx-auto">
						<table class="table table-borderless">
							<tr>
								<td width="200">Nama: {{ $tester['nama'] }}</td>
								<td width="200">Usia: {{ $tester['usia'] }} tahun</td>
								<td width="200">Jenis Kelamin: {{ $tester['gender'] }}</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-auto mb-5">
						<table class="table-bordered">
							<thead bgcolor="#bebebe">
								<tr>
									<th width="50" rowspan="2">#</th>
									<th colspan="2">MOST</th>
									<th colspan="2">LEAST</th>
								</tr>
								<tr>
									<th width="80">Jumlah</th>
									<th width="80">Skor</th>
									<th width="80">Jumlah</th>
									<th width="80">Skor</th>
								</tr>
							</thead>
							<tbody>
								@foreach($disc as $letter)
								<tr>
									<td align="center" bgcolor="#bebebe"><strong>{{ $letter }}</strong></td>
									<td align="center">{{ array_key_exists($letter, $array_count_m) ? $array_count_m[$letter] : 0 }}</td>
									<td align="center" bgcolor="#eeeeee">{{ $disc_score_m[$letter]['score'] }}</td>
									<td align="center">{{ array_key_exists($letter, $array_count_l) ? $array_count_l[$letter] : 0 }}</td>
									<td align="center" bgcolor="#eeeeee">{{ $disc_score_l[$letter]['score'] }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="row mt-4">
							<div class="col">
								<p class="text-center mb-0 font-weight-bold">Response to the Environment</p>
								<p class="text-center mb-0 font-weight-bold">MOST</p>
								<p class="text-center mb-0 font-weight-bold">Adapted: (<?php echo implode("-", $code_m) ?>)</p>
								<canvas class="mt-3" id="mostChart" width="200" height="200"></canvas>
							</div>
						</div>
						<div class="row mt-4">
							<div class="col">
								<p class="text-center mb-0 font-weight-bold">Basic Style</p>
								<p class="text-center mb-0 font-weight-bold">LEAST</p>
								<p class="text-center mb-0 font-weight-bold">Natural: (<?php echo implode("-", $code_l) ?>)</p>
								<canvas class="mt-3" id="leastChart" width="200" height="200"></canvas>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="row">
							<div class="col">
								<p class="h4 text-center font-weight-bold mb-3">Deskripsi</p>
								<div class="p-2 deskripsi">{!! html_entity_decode($hasil_keterangan) !!}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
<script>
var ctx1 = document.getElementById('mostChart').getContext('2d');
var mostChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['D', 'I', 'S', 'C'],
        datasets: [{
            label: 'Score',
            data: [{{ $disc_score_m['D']['score'] }}, {{ $disc_score_m['I']['score'] }}, {{ $disc_score_m['S']['score'] }}, {{ $disc_score_m['C']['score'] }}],
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
            data: [{{ $disc_score_l['D']['score'] }}, {{ $disc_score_l['I']['score'] }}, {{ $disc_score_l['S']['score'] }}, {{ $disc_score_l['C']['score'] }}],
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
        }
    }
});

$(document).on("click", ".btn-print", function(e){
	e.preventDefault();
	window.print();
});
</script>
<style type="text/css">
	table tr th, table tr td {padding: .25rem .5rem; text-align: center;}
	.deskripsi {border-style: groove; columns: 2; font-size: smaller;}
	.deskripsi p {margin-bottom: .5rem;}
</style>
</html>