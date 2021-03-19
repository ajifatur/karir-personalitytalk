<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<title>Registrasi</title>
</head>
<body>
	<div class="container-fluid my-5">
		<div class="card bg-light">
			<div class="card-title py-3">
				<h1 class="text-center">REGISTRASI</h1>
			</div>
			<div class="card-body">
				<form method="post" action="/disc/test">
					{{ csrf_field() }}
					<div class="container-fluid">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
			    					<label for="nama">Nama</label>
			    					<input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" placeholder="Masukkan Nama" value="{{ old('nama') }}" autofocus>
			    					@if($errors->has('nama'))
										<div class="invalid-feedback">
											{{ ucfirst($errors->first('nama')) }}
										</div>
									@endif
			  					</div>
			  				</div>
							<div class="col-sm-6">
								<div class="form-group">
			    					<label for="usia">Usia</label>
			    					<input type="text" name="usia" class="form-control {{ $errors->has('usia') ? 'is-invalid' : '' }}" placeholder="Masukkan Usia" value="{{ old('usia') }}">
			    					@if($errors->has('usia'))
										<div class="invalid-feedback">
											{{ ucfirst($errors->first('usia')) }}
										</div>
									@endif
			  					</div>
			  				</div>
							<div class="col-sm-6">
								<div class="form-group">
			    					<label for="email">Email</label>
			    					<input type="text" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Masukkan Email" value="{{ old('email') }}">
			    					@if($errors->has('email'))
										<div class="invalid-feedback">
											{{ ucfirst($errors->first('email')) }}
										</div>
									@endif
			  					</div>
			  				</div>
							<div class="col-sm-6">
								<div class="form-group">
			    					<label for="gender">Gender</label>
			    					<select name="gender" class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}">
			    						<option value="" disabled selected>--Pilih--</option>
			    						<option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
			    						<option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
			    					</select>
			    					@if($errors->has('gender'))
										<div class="invalid-feedback">
											{{ ucfirst($errors->first('gender')) }}
										</div>
									@endif
			  					</div>
			  				</div>
			  			</div>
						<div class="row mt-3">
			  				<div class="col-auto mx-auto">
			  					<button type="submit" class="btn btn-md btn-primary text-uppercase rounded-0">Submit</button>
			  				</div>
			  			</div>
			  		</div>
				</form>
			</div>
		</div>
	</div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</script>
</html>