@extends('template/admin/main')

@section('content')

    <!-- Page Heading -->
    <div class="page-heading shadow d-flex justify-content-between align-items-center">
        <h1 class="h3 text-gray-800">Edit STIFIn</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fas fa-tachometer-alt"></i></li>
            <li class="breadcrumb-item"><a href="/admin/stifin">STIFIn</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit STIFIn</li>
        </ol>
    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="post" action="/admin/stifin/update">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $stifin->id }}">
                <div class="form-group row">
                    <label class="col-lg-2 col-md-3 col-form-label">Nama: <span class="text-danger">*</span></label>
                    <div class="col-lg-10 col-md-9">
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $stifin->name }}">
                        @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ ucfirst($errors->first('name')) }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-2 col-md-3 col-form-label">Tes: <span class="text-danger">*</span></label>
                    <div class="col-lg-10 col-md-9">
                        <select name="test" class="form-control {{ $errors->has('test') ? 'is-invalid' : '' }} custom-select">
                            <option value="" disabled selected>--Pilih--</option>
                            @foreach($tests as $test)
                            <option value="{{ $test->id_st }}" {{ $stifin->test == $test->id_st ? 'selected' : '' }}>{{ $test->test_name }}</option>
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
                        <input name="birthdate" type="text" class="form-control {{ $errors->has('birthdate') ? 'is-invalid' : '' }}" value="{{ generate_date_format($stifin->birthdate, 'd/m/y') }}">
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
                        <input name="test_at" type="text" class="form-control {{ $errors->has('test_at') ? 'is-invalid' : '' }}" value="{{ generate_date_format($stifin->test_at, 'd/m/y') }}">
                        @if($errors->has('test_at'))
                        <div class="invalid-feedback">
                            {{ ucfirst($errors->first('test_at')) }}
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