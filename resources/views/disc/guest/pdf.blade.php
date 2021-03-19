<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
	<title>TABEL</title>
</head>
<body>
	<div class="container-fluid mt-5">
		<div class="card bg-light">
			<div class="card-title py-3">
				<h1 class="text-center">TABEL</h1>
			</div>
			<div class="card-body">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
			              <th width="50">No.</th>
			              <th>Paket Soal</th>
			              <th width="50">Jumlah</th>
			              <th width="100">Status</th>
						</tr>
					</thead>
					<tbody>
						@foreach($paket_soal as $data)
						<tr>
							<td>{{ $data->id_paket }}</td>
							<td>{{ $data->nama_paket }}</td>
							<td>{{ $data->jumlah_soal }}</td>
							<td>{{ $data->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</html>