@extends('template/admin/main')

@section('content')

    <!-- Page Heading -->
    <div class="page-heading shadow d-flex justify-content-between align-items-center">
        <h1 class="h3 text-gray-800">Tambah STIFIn</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
            <li class="breadcrumb-item"><a href="/admin/stifin">STIFIn</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah STIFIn</li>
        </ol>
    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post" action="/admin/stifin/store">
                {{ csrf_field() }}
                <div class="form-group row">
                    <label class="col-lg-2 col-md-3 col-form-label">Nama: <span class="text-danger">*</span></label>
                    <div class="col-lg-10 col-md-9">
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}">
                        @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ ucfirst($errors->first('name')) }}
                        </div>
                        @endif
                    </div>
                </div>
				<div class="form-group row">
				  <label class="col-lg-2 col-md-3 col-form-label">Jenis Kelamin: <span class="text-danger">*</span></label>
				  <div class="col-lg-10 col-md-9">
					<select name="jenis_kelamin" class="form-control {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }} custom-select">
						<option value="" disabled selected>--Pilih--</option>
						<option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
						<option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
					</select>
					@if($errors->has('jenis_kelamin'))
					<div class="invalid-feedback">
					  {{ ucfirst($errors->first('jenis_kelamin')) }}
					</div>
					@endif
				  </div>
				</div>
                @if(Auth::user()->role == role_admin())
                <div class="form-group row">
                    <label class="col-lg-2 col-md-3 col-form-label">Perusahaan: <span class="text-danger">*</span></label>
                    <div class="col-lg-10 col-md-9">
                        <select name="hrd" class="form-control custom-select {{ $errors->has('hrd') ? 'is-invalid' : '' }}">
                            <option value="" disabled selected>--Pilih--</option>
                            @foreach($hrd as $data)
                            <option value="{{ $data->id_hrd }}" {{ $data->id_hrd == old('hrd') ? 'selected' : '' }}>{{ $data->perusahaan }} ({{ $data->nama_lengkap }})</option>
                            @endforeach
                        </select>
                        @if($errors->has('hrd'))
                        <div class="invalid-feedback">
                            {{ ucfirst($errors->first('hrd')) }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                <div class="form-group row">
                    <label class="col-lg-2 col-md-3 col-form-label">Tipe: <span class="text-danger">*</span></label>
                    <div class="col-lg-10 col-md-9">
                        <select name="test" class="form-control {{ $errors->has('test') ? 'is-invalid' : '' }} custom-select">
                            <option value="" disabled selected>--Pilih--</option>
                            @foreach($tests as $test)
                            <option value="{{ $test->id_st }}" {{ old('test') == $test->id_st ? 'selected' : '' }}>{{ $test->test_name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('test'))
                        <div class="invalid-feedback">
                            {{ ucfirst($errors->first('test')) }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-md-3 col-form-label">Tanggal Lahir:</label>
                    <div class="col-lg-10 col-md-9">
                        <input name="birthdate" type="text" class="form-control {{ $errors->has('birthdate') ? 'is-invalid' : '' }}" value="{{ old('birthdate') }}">
                        @if($errors->has('birthdate'))
                        <div class="invalid-feedback">
                            {{ ucfirst($errors->first('birthdate')) }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-md-3 col-form-label">Tanggal Tes:</label>
                    <div class="col-lg-10 col-md-9">
                        <input name="test_at" type="text" class="form-control {{ $errors->has('test_at') ? 'is-invalid' : '' }}" value="{{ old('test_at') }}">
                        @if($errors->has('test_at'))
                        <div class="invalid-feedback">
                            {{ ucfirst($errors->first('test_at')) }}
                        </div>
                        @endif
                    </div>
                </div>
				<div class="form-group row">
				  <label class="col-lg-2 col-md-3 col-form-label">Tujuan Tes: <span class="text-danger">*</span></label>
				  <div class="col-lg-10 col-md-9">
					<select name="aim" class="form-control {{ $errors->has('aim') ? 'is-invalid' : '' }} custom-select">
						<option value="" disabled selected>--Pilih--</option>
						@foreach($aims as $aim)
						<option value="{{ $aim->id_sa }}" {{ old('aim') == $aim->id_sa ? 'selected' : '' }}>{{ $aim->aim }}</option>
						@endforeach
					</select>
					@if($errors->has('aim'))
					<div class="invalid-feedback">
					  {{ ucfirst($errors->first('aim')) }}
					</div>
					@endif
				  </div>
				</div>
                <div class="form-group row">
                    <div class="col-lg-2 col-md-3"></div>
                    <div class="col-lg-10 col-md-9">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="/admin/stifin" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js-extra')

<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha256-bqVeqGdJ7h/lYPq6xrPv/YGzMEb6dNxlfiTUHSgRCp8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Datepicker
        $("input[name=birthdate], input[name=test_at]").datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            todayHighlight: true,
        });
    });
</script>

@endsection

@section('css-extra')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha256-siyOpF/pBWUPgIcQi17TLBkjvNgNQArcmwJB8YvkAgg=" crossorigin="anonymous" />

@endsection